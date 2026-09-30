<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use App\Models\Produit;
use App\Models\Vente;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $debut = now()->startOfMonth();
        $fin   = now()->endOfMonth();

        // --- Chiffres du mois (ventes annulées exclues) ---
        $lignesMois = DB::table('details_ventes')
            ->join('ventes', 'ventes.id', '=', 'details_ventes.vente_id')
            ->join('produits', 'produits.id', '=', 'details_ventes.produit_id')
            ->whereNull('ventes.annulee_le')
            ->whereBetween('ventes.date_vente', [$debut, $fin]);

        $ca         = (clone $lignesMois)->sum('details_ventes.sous_total');
        $coutAchat  = (clone $lignesMois)->sum(DB::raw('details_ventes.quantite * produits.prix_achat'));
        $depenses   = Depense::whereBetween('date_depense', [$debut->toDateString(), $fin->toDateString()])->sum('montant');
        $benefice   = $ca - $coutAchat - $depenses;
        $nbVentes   = Vente::nonAnnulees()->whereBetween('date_vente', [$debut, $fin])->count();

        $topProduits = (clone $lignesMois)
            ->select('produits.nom', DB::raw('SUM(details_ventes.quantite) as quantite'), DB::raw('SUM(details_ventes.sous_total) as total'))
            ->groupBy('produits.id', 'produits.nom')
            ->orderByDesc('quantite')
            ->limit(5)
            ->get();

        // --- Ventes du jour ---
        $ventesJour = Vente::nonAnnulees()->whereDate('date_vente', today());
        $nbVentesJour = (clone $ventesJour)->count();
        
        // Comme pour le CA du mois : prix des parfums seulement, la livraison n'est pas ton argent
        $caJour = DB::table('details_ventes')
            ->join('ventes', 'ventes.id', '=', 'details_ventes.vente_id')
            ->whereNull('ventes.annulee_le')
            ->whereDate('ventes.date_vente', today())
            ->sum('details_ventes.sous_total');
        // --- Impayés, du plus ancien au plus récent ---
        $impayes = Vente::nonAnnulees()
            ->with(['client', 'paiements'])
            ->whereIn('statut_paiement', ['impaye', 'partiel'])
            ->orderBy('date_vente')
            ->get();
        $totalImpayes = $impayes->sum(fn ($v) => $v->reste_a_payer);

        // --- Stock bas ---
        $stockBas = Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte')
            ->orderBy('quantite_stock')
            ->get();

        // --- Stock dormant ---
        $derniereVente = DB::table('details_ventes')
            ->join('ventes', 'ventes.id', '=', 'details_ventes.vente_id')
            ->whereNull('ventes.annulee_le')
            ->select('details_ventes.produit_id', DB::raw('MAX(ventes.date_vente) as derniere'))
            ->groupBy('details_ventes.produit_id')
            ->pluck('derniere', 'produit_id');

        // Les entrées liées à une vente annulée ne comptent pas comme un réapprovisionnement
        $derniereEntree = DB::table('mouvements_stock')
            ->where('type', 'entree')
            ->whereNull('vente_id')
            ->select('produit_id', DB::raw('MAX(date_mouvement) as derniere'))
            ->groupBy('produit_id')
            ->pluck('derniere', 'produit_id');

        $limite = now()->subDays(30);

        $dormants = Produit::where('quantite_stock', '>', 0)->get()
            ->map(function ($produit) use ($derniereVente, $derniereEntree) {
                // Date de référence : le dernier événement parmi vente, entrée en stock, création
                $dates = collect([
                    $derniereVente[$produit->id] ?? null,
                    $derniereEntree[$produit->id] ?? null,
                    $produit->created_at,
                ])->filter()->map(fn ($d) => Carbon::parse($d));

                $produit->derniere_activite = $dates->max();
                return $produit;
            })
            ->filter(fn ($p) => $p->derniere_activite && $p->derniere_activite->lt($limite))
            ->sortBy('derniere_activite')
            ->values();

        $argentImmobilise = $dormants->sum(fn ($p) => $p->quantite_stock * $p->prix_achat);

        return view('dashboard', [
            'moisLabel'        => now()->locale('fr')->translatedFormat('F Y'),
            'ca'               => $ca,
            'benefice'         => $benefice,
            'depenses'         => $depenses,
            'nbVentes'         => $nbVentes,
            'topProduits'      => $topProduits,
            'nbVentesJour'     => $nbVentesJour,
            'caJour'           => $caJour,
            'impayes'          => $impayes,
            'totalImpayes'     => $totalImpayes,
            'stockBas'         => $stockBas,
            'dormants'         => $dormants,
            'argentImmobilise' => $argentImmobilise,
        ]);
    }
}