<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    protected $table = 'ventes';

    protected $fillable = [
        'client_id', 'utilisateur_id', 'date_vente', 'montant_total',
        'frais_livraison', 'mode_livraison', 'statut_paiement',
    ];

    protected $casts = [
        'date_vente' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function detailsVentes()
    {
        return $this->hasMany(DetailVente::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function getMontantPayeAttribute(): float
    {
        return (float) $this->paiements->sum('montant');
    }

    public function getResteAPayerAttribute(): float
    {
        return max(0, (float) $this->montant_total - $this->montant_paye);
    }
}