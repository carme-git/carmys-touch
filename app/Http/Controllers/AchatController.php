<?php

namespace App\Http\Controllers;

use App\Http\Requests\AchatRequest;
use App\Models\Achat;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\Vente;
use App\Services\StockService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AchatController extends Controller
{
    public function __construct(private StockService $stock)
    {
    }

    public function index()
    {
        $achats = Achat::with('fournisseur')->latest('date_achat')->latest('id')->paginate(15);

        return view('achats.index', compact('achats'));
    }

    public function create()
    {
        return view('achats.create', [
            'fournisseurs' => Fournisseur::orderBy('nom')->get(),
            'produits'     => Produit::orderBy('nom')->get(),
            'ventes'       => Vente::with('client')->latest('date_vente')->take(50)->get(),
        ]);
    }

    public function store(AchatRequest $request)
    {
        $achat = DB::transaction(function () use ($request) {
            $achat = Achat::create([
                'fournisseur_id' => $request->fournisseur_id,
                'utilisateur_id' => Auth::id(),
                'type'           => $request->type,
                // Le lien vers une vente n'a de sens que pour un achat sur commande
                'vente_id'       => $request->type === 'commande_client' ? $request->vente_id : null,
                'date_achat'     => $request->date_achat,
                'statut'         => 'valide',
            ]);

            foreach ($request->lignes as $ligne) {
                $achat->detailsAchats()->create([
                    'produit_id'    => $ligne['produit_id'],
                    'quantite'      => $ligne['quantite'],
                    'prix_unitaire' => $ligne['prix_unitaire'],
                ]);

                $this->stock->entree($ligne['produit_id'], $ligne['quantite'], $achat->id);
            }

            $achat->update(['montant_total' => $achat->detailsAchats()->sum('sous_total')]);

            return $achat;
        });

        return redirect()->route('achats.show', $achat)->with('success', 'Achat enregistré, stock mis à jour.');
    }

    public function show(Achat $achat)
    {
        $achat->load(['fournisseur', 'utilisateur', 'vente.client', 'detailsAchats.produit']);

        return view('achats.show', compact('achat'));
    }
}