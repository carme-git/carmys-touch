<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailVente extends Model
{
    protected $table = 'details_ventes';
    public $timestamps = false;
    protected $fillable = ['vente_id', 'produit_id', 'quantite', 'prix_unitaire', 'remise'];

    public function vente()
    {
        return $this->belongsTo(Vente::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}