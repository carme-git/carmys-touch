<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    protected $table = 'utilisateurs';
    protected $fillable = ['role_id', 'nom', 'prenom', 'email', 'telephone', 'mot_de_passe', 'statut'];
    protected $hidden = ['mot_de_passe'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function achats()
    {
        return $this->hasMany(Achat::class);
    }

    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }

    public function inventaires()
    {
        return $this->hasMany(Inventaire::class);
    }

    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class, 'utilisateur_id');
    }

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function journauxActivites()
    {
        return $this->hasMany(JournalActivite::class);
    }
}