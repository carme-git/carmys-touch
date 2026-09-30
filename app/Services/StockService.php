<?php

namespace App\Services;

use App\Models\MouvementStock;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;

class StockService
{
    // À appeler DANS une transaction : lockForUpdate n'a d'effet que dedans
    public function entree(int $produitId, int $quantite, ?int $achatId = null): Produit
    {
        $produit = Produit::lockForUpdate()->findOrFail($produitId);
        $produit->increment('quantite_stock', $quantite);
        $this->journaliser($produit->id, 'entree', $quantite, achatId: $achatId);

        return $produit;
    }

    public function sortie(int $produitId, int $quantite, ?int $venteId = null): Produit
    {
        $produit = Produit::lockForUpdate()->findOrFail($produitId);

        if ($produit->quantite_stock < $quantite) {
            throw new \RuntimeException(
                "Stock insuffisant pour « {$produit->nom} » : {$produit->quantite_stock} disponible(s)."
            );
        }

        $produit->decrement('quantite_stock', $quantite);
        $this->journaliser($produit->id, 'sortie', $quantite, venteId: $venteId);

        return $produit;
    }

    private function journaliser(int $produitId, string $type, int $quantite, ?int $achatId = null, ?int $venteId = null): void
    {
        MouvementStock::create([
            'produit_id'     => $produitId,
            'type'           => $type,
            'quantite'       => $quantite,
            'achat_id'       => $achatId,
            'vente_id'       => $venteId,
            'utilisateur_id' => Auth::id(),
            'date_mouvement' => now(),
        ]);
    }
}