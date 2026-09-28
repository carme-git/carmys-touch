<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    protected $table = 'ventes';
    protected $fillable = ['client_id', 'utilisateur_id', 'date_vente', 'montant_total', 'statut_paiement'];

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

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }

    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class);
    }
}