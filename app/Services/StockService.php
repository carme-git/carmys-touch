<?php

namespace App\Services;

use App\Models\MouvementStock;
use App\Models\Produit;
use App\Models\Vente;
use Illuminate\Support\Facades\Auth;

class StockService
{
    // À appeler DANS une transaction : lockForUpdate n'a d'effet que dedans
    public function entree(int $produitId, int $quantite, ?int $achatId = null, ?int $venteId = null): Produit
    {
        $produit = Produit::lockForUpdate()->findOrFail($produitId);
        $produit->increment('quantite_stock', $quantite);
        $this->journaliser($produit->id, 'entree', $quantite, achatId: $achatId, venteId: $venteId);

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

    // Annulation d'une vente : chaque parfum vendu revient en stock, avec une trace dans l'historique
    public function retourAnnulation(Vente $vente): void
    {
        foreach ($vente->detailsVentes as $ligne) {
            $this->entree($ligne->produit_id, $ligne->quantite, venteId: $vente->id);
        }
    }

    // Correction d'inventaire : on fixe le stock à la quantité réellement comptée.
    // La quantité du mouvement est l'écart, avec son signe (+3 ou -2). Renvoie l'écart (0 = rien à corriger).
    public function correction(int $produitId, int $nouvelleQuantite): int
    {
        $produit = Produit::lockForUpdate()->findOrFail($produitId);
        $ecart   = $nouvelleQuantite - (int) $produit->quantite_stock;

        if ($ecart === 0) {
            return 0;
        }

        $produit->quantite_stock = $nouvelleQuantite;
        $produit->save();
        $this->journaliser($produit->id, 'correction', $ecart);

        return $ecart;
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