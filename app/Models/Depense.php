<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    protected $table = 'depenses';
    public $timestamps = false;
    protected $fillable = ['categorie', 'montant', 'description', 'utilisateur_id', 'date_depense'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
}