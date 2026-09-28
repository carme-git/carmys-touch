<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('produits', App\Http\Controllers\ProduitController::class)->except('show');
Route::resource('clients', App\Http\Controllers\ClientController::class);

// Routes provisoires (à remplacer par les vrais contrôleurs au fur et à mesure)
Route::view('/ventes', 'placeholder', ['titre' => 'Ventes'])->name('ventes.index');
Route::view('/achats', 'placeholder', ['titre' => 'Achats'])->name('achats.index');
Route::view('/fournisseurs', 'placeholder', ['titre' => 'Fournisseurs'])->name('fournisseurs.index');
Route::view('/depenses', 'placeholder', ['titre' => 'Dépenses'])->name('depenses.index');

Route::post('/logout', function () {
    return redirect()->route('dashboard');
})->name('logout');