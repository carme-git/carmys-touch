<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $table = 'factures';

    protected $fillable = ['vente_id', 'numero_facture', 'date_facture'];

    protected $casts = ['date_facture' => 'date'];

    public function vente()
    {
        return $this->belongsTo(Vente::class);
    }
}