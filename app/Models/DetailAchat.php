<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailAchat extends Model
{
    protected $table = 'details_achats';
    public $timestamps = false;
    protected $fillable = ['achat_id', 'produit_id', 'quantite', 'prix_unitaire'];

    public function achat()
    {
        return $this->belongsTo(Achat::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}