<?php

use App\Http\Controllers\AchatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\VenteController;
use Illuminate\Support\Facades\Route;

// Pages accessibles seulement si on n'est PAS connectée
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login'])->name('login.attempt');
});

// Tout le reste exige d'être connectée
Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('produits', ProduitController::class)->except('show');
    Route::resource('clients', ClientController::class);

    Route::resource('ventes', VenteController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('ventes/{vente}/paiements', [PaiementController::class, 'store'])->name('paiements.store');
    Route::delete('paiements/{paiement}', [PaiementController::class, 'destroy'])->name('paiements.destroy');

    Route::resource('achats', AchatController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('fournisseurs', FournisseurController::class);
    Route::resource('depenses', DepenseController::class)->except('show');
});