<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    public $timestamps = false;
    protected $fillable = ['utilisateur_id', 'type', 'message', 'lue'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
}