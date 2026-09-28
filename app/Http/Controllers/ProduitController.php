<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index()
{
    $produits = Produit::with('categorie')->get();
    return view('produits.index', compact('produits'));
}
}