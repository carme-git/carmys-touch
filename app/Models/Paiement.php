<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = 'paiements';

    protected $fillable = ['vente_id', 'montant', 'mode_paiement', 'reference', 'date_paiement'];

    protected $casts = [
        'date_paiement' => 'datetime',
    ];

    public function vente()
    {
        return $this->belongsTo(Vente::class);
    }
}