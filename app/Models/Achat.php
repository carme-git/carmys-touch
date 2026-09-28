<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achat extends Model
{
    protected $table = 'achats';
    protected $fillable = ['fournisseur_id', 'utilisateur_id', 'date_achat', 'montant_total', 'statut'];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function detailsAchats()
    {
        return $this->hasMany(DetailAchat::class);
    }

    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class);
    }
}