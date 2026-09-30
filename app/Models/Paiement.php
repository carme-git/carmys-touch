<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = 'paiements';

    protected $fillable = [
        'vente_id', 'montant', 'mode_paiement', 'reference',
        'date_paiement', 'rembourse_le',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'rembourse_le'  => 'datetime',
    ];

    public function vente()
    {
        return $this->belongsTo(Vente::class);
    }

    public function estRembourse(): bool
    {
        return $this->rembourse_le !== null;
    }
}