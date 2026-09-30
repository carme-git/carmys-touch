<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achat extends Model
{
    protected $table = 'achats';

    protected $fillable = [
        'fournisseur_id', 'utilisateur_id', 'type', 'vente_id',
        'date_achat', 'montant_total', 'statut',
    ];

    protected $casts = ['date_achat' => 'date'];

    public function fournisseur()  { return $this->belongsTo(Fournisseur::class); }
    public function utilisateur()  { return $this->belongsTo(Utilisateur::class); }
    public function vente()        { return $this->belongsTo(Vente::class); }
    public function detailsAchats(){ return $this->hasMany(DetailAchat::class); }
}