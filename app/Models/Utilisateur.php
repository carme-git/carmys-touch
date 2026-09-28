<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Utilisateur extends Authenticatable
{
    protected $table = 'utilisateurs';

    protected $fillable = ['role_id', 'nom', 'prenom', 'email', 'telephone', 'mot_de_passe'];

    protected $hidden = ['mot_de_passe', 'remember_token'];

    protected function casts(): array
    {
        return ['mot_de_passe' => 'hashed'];
    }

    // Dit à Laravel où lire le mot de passe (sa colonne s'appelle mot_de_passe, pas password)
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function getNomCompletAttribute()
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}