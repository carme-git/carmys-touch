<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    protected $table = 'mouvements_stock';

    public $timestamps = false;

    protected $fillable = [
        'produit_id', 'type', 'quantite', 'achat_id', 'vente_id',
        'inventaire_id', 'utilisateur_id', 'date_mouvement',
    ];

    protected $casts = ['date_mouvement' => 'datetime'];

    public function produit() { return $this->belongsTo(Produit::class); }
    public function vente()   { return $this->belongsTo(Vente::class); }
    public function achat()   { return $this->belongsTo(Achat::class); }
}