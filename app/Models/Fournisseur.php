<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $table = 'fournisseurs';

    protected $fillable = ['nom', 'telephone', 'adresse', 'email'];

    public function achats() { return $this->hasMany(Achat::class); }
}