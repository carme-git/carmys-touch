<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $table = 'produits';
    protected $fillable = [
        'category_id', 'nom', 'reference', 'prix_achat', 'prix_vente',
        'quantite_stock', 'seuil_alerte', 'description', 'image',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'category_id');
    }

    public function detailsAchats()
    {
        return $this->hasMany(DetailAchat::class);
    }

    public function detailsVentes()
    {
        return $this->hasMany(DetailVente::class);
    }

    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class);
    }

    public function inventaires()
    {
        return $this->hasMany(Inventaire::class);
    }
}