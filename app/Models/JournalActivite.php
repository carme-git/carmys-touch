<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalActivite extends Model
{
    protected $table = 'journaux_activites';
    public $timestamps = false;
    protected $fillable = ['utilisateur_id', 'action', 'entite', 'entite_id'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
}