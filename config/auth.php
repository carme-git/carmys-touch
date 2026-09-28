<?php

use App\Models\Utilisateur;

return [

    // Guard et broker de mots de passe utilisés par défaut
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    // Le guard "web" garde la connexion en session
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    // Où Laravel va chercher les personnes qui se connectent :
    // ici, le modèle Utilisateur (table "utilisateurs")
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', Utilisateur::class),
        ],
    ],

    // Réinitialisation de mot de passe (pas encore utilisée dans ton projet)
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    // Délai avant de redemander le mot de passe pour une action sensible (3 h)
    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];