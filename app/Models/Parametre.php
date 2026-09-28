<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    protected $table = 'parametres';
    public $timestamps = false;
    protected $fillable = ['nom_entreprise', 'logo', 'adresse', 'telephone', 'email', 'devise', 'numero_fiscal'];
}