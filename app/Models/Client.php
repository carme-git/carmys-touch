<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = ['nom', 'prenom', 'telephone', 'adresse', 'email'];

    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }

    public function getNomCompletAttribute(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    // Total que la cliente doit encore payer, toutes ventes confondues
    public function getSoldeImpayeAttribute(): float
    {
        return (float) $this->ventes->sum(fn ($vente) => $vente->reste_a_payer);
    }
}