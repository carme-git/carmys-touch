<?php

namespace App\Http\Controllers;

use App\Http\Requests\VenteRequest;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Vente;
use App\Services\StockService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
    public function __construct(private StockService $stock)
    {
    }

    public function index()
    {
        // with(...) charge client et paiements en une fois : évite une requête par ligne
        $ventes = Vente::with(['client', 'paiements'])
            ->latest('date_vente')
            ->paginate(15);

        return view('ventes.index', compact('ventes'));
    }

    public function create()
    {
        $clients  = Client::orderBy('nom')->get();
        $produits = Produit::orderBy('nom')->get();

        return view('ventes.create', compact('clients', 'produits'));
    }

    public function store(VenteRequest $request)
    {
        // Une vente sans cliente ne peut pas laisser d'impayé : personne ne pourrait le réclamer
        $sansCliente = ! $request->client_id && blank($request->client_nom);
        if ($sansCliente && ! $request->boolean('paye_maintenant')) {
            return back()->withInput()->withErrors([
                'client_id' => 'Une vente sans cliente doit être payée en totalité maintenant.',
            ]);
        }

        try {
            // Tout ou rien : si une étape échoue, ni la vente, ni le stock, ni la cliente ne sont gardés
            $vente = DB::transaction(function () use ($request) {
                // Cliente choisie dans la liste, sinon nouvelle cliente créée à la volée, sinon aucune
                $clientId = $request->client_id;

                if (! $clientId && filled($request->client_nom)) {
                    $clientId = Client::create([
                        'nom'       => $request->client_nom,
                        'telephone' => $request->client_telephone,
                    ])->id;
                }

                $vente = Vente::create([
                    'client_id'       => $clientId,
                    'utilisateur_id'  => Auth::id(),
                    'date_vente'      => $request->date_vente,
                    'mode_livraison'  => $request->mode_livraison,
                    'frais_livraison' => $request->frais_livraison ?? 0,
                ]);

                foreach ($request->lignes as $ligne) {
                    // Le service vérifie le stock, le diminue et l'écrit dans l'historique
                    $produit = $this->stock->sortie($ligne['produit_id'], $ligne['quantite'], $vente->id);

                    $vente->detailsVentes()->create([
                        'produit_id'    => $produit->id,
                        'quantite'      => $ligne['quantite'],
                        'prix_unitaire' => $ligne['prix_unitaire'],
                        'remise'        => $ligne['remise'] ?? 0,
                    ]);
                }

                // Le total vient des sous_total calculés par MySQL
                $vente->update([
                    'montant_total' => $vente->detailsVentes()->sum('sous_total') + $vente->frais_livraison,
                ]);

                // Vente payée sur place : on crée le paiement complet et on met le statut à jour
                if ($request->boolean('paye_maintenant')) {
                    $vente->paiements()->create([
                        'montant'       => $vente->montant_total,
                        'mode_paiement' => $request->mode_paiement_immediat ?? 'especes',
                        'date_paiement' => $request->date_vente,
                    ]);
                    $vente->recalculerStatut();
                }

                return $vente;
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->withErrors(['lignes' => $e->getMessage()]);
        }

        return redirect()->route('ventes.show', $vente)->with('success', 'Vente enregistrée.');
    }

    public function show(Vente $vente)
    {
        $vente->load(['client', 'utilisateur', 'detailsVentes.produit', 'paiements']);

        return view('ventes.show', compact('vente'));
    }
}