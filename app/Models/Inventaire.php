<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventaire extends Model
{
    protected $table = 'inventaires';
    public $timestamps = false;
    protected $fillable = ['produit_id', 'utilisateur_id', 'quantite_theorique', 'quantite_constatee', 'justification', 'date_inventaire'];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class);
    }
}