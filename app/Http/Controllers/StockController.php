<?php

namespace App\Http\Controllers;

use App\Models\MouvementStock;
use App\Models\Produit;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function __construct(private StockService $stock)
    {
    }

    // Historique de tous les mouvements, filtrable par parfum
    public function index(Request $request)
    {
        $mouvements = MouvementStock::with('produit')
            ->when($request->produit_id, fn ($q, $id) => $q->where('produit_id', $id))
            ->orderByDesc('date_mouvement')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        $produits = Produit::orderBy('nom')->get(['id', 'nom']);

        return view('stock.index', compact('mouvements', 'produits'));
    }

    // Page de comptage : une ligne par parfum
    public function inventaire()
    {
        $produits = Produit::orderBy('nom')->get();

        return view('stock.inventaire', compact('produits'));
    }

    public function enregistrerInventaire(Request $request)
    {
        $request->validate([
            'comptes'   => ['required', 'array'],
            'comptes.*' => ['nullable', 'integer', 'min:0'],
        ], [
            'comptes.*.integer' => 'Chaque quantité comptée doit être un nombre entier.',
            'comptes.*.min'     => 'Une quantité comptée ne peut pas être négative.',
        ]);

        $nbCorrections = 0;

        DB::transaction(function () use ($request, &$nbCorrections) {
            foreach ($request->comptes as $produitId => $compte) {
                // Case laissée vide = parfum non compté, on n'y touche pas
                if ($compte === null || $compte === '') {
                    continue;
                }

                if ($this->stock->correction((int) $produitId, (int) $compte) !== 0) {
                    $nbCorrections++;
                }
            }
        });

        return redirect()->route('stock.index')
            ->with('success', "Inventaire enregistré : {$nbCorrections} correction(s).");
    }
}