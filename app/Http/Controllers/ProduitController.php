<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProduitRequest;
use App\Models\Categorie;
use App\Models\Produit;
use App\Services\StockService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    public function __construct(private StockService $stock)
    {
    }

    public function index(Request $request)
    {
        $produits = Produit::with('categorie')
            ->when($request->q, fn ($query, $terme) => $query->where('nom', 'like', "%{$terme}%"))
            ->orderBy('nom')
            ->get();

        return view('produits.index', compact('produits'));
    }

    public function create()
    {
        return view('produits.create', [
            'produit'    => new Produit(),
            'categories' => Categorie::orderBy('nom')->get(),
        ]);
    }

    public function store(ProduitRequest $request)
    {
        $data = $request->validated();

        // Le stock de départ passe par le service : il laisse une trace dans l'historique
        $stockInitial = (int) ($data['quantite_stock'] ?? 0);
        $data['quantite_stock'] = 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('produits', 'public');
        }

        DB::transaction(function () use ($data, $stockInitial) {
            $produit = Produit::create($data);

            if ($stockInitial > 0) {
                $this->stock->correction($produit->id, $stockInitial);
            }
        });

        return redirect()->route('produits.index')->with('success', 'Produit ajouté.');
    }

    public function edit(Produit $produit)
    {
        return view('produits.edit', [
            'produit'    => $produit,
            'categories' => Categorie::orderBy('nom')->get(),
        ]);
    }

    public function update(ProduitRequest $request, Produit $produit)
    {
        // Le stock ne se modifie jamais ici : uniquement par vente, achat ou inventaire
        $data = Arr::except($request->validated(), ['quantite_stock']);

        if ($request->hasFile('image')) {
            if ($produit->image) {
                Storage::disk('public')->delete($produit->image);
            }
            $data['image'] = $request->file('image')->store('produits', 'public');
        }

        $produit->update($data);

        return redirect()->route('produits.index')->with('success', 'Produit modifié.');
    }

    public function destroy(Produit $produit)
    {
        try {
            $produit->delete();
        } catch (QueryException $e) {
            return redirect()->route('produits.index')
                ->with('error', 'Impossible de supprimer : ce produit est déjà utilisé dans des ventes ou des achats.');
        }

        if ($produit->image) {
            Storage::disk('public')->delete($produit->image);
        }

        return redirect()->route('produits.index')->with('success', 'Produit supprimé.');
    }
}