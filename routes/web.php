<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProduitController;
use Illuminate\Support\Facades\Route;

// Pages accessibles seulement si on n'est PAS connectée
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login'])->name('login.attempt');
});

// Tout le reste exige d'être connectée
Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('produits', ProduitController::class)->except('show');
    Route::resource('clients', ClientController::class);

    Route::view('/ventes', 'placeholder', ['titre' => 'Ventes'])->name('ventes.index');
    Route::view('/achats', 'placeholder', ['titre' => 'Achats'])->name('achats.index');
    Route::view('/fournisseurs', 'placeholder', ['titre' => 'Fournisseurs'])->name('fournisseurs.index');
    Route::view('/depenses', 'placeholder', ['titre' => 'Dépenses'])->name('depenses.index');
});