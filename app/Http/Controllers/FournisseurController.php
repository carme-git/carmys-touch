<?php

namespace App\Http\Controllers;

use App\Http\Requests\FournisseurRequest;
use App\Models\Fournisseur;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index(Request $request)
    {
        $fournisseurs = Fournisseur::withCount('achats')
            ->when($request->q, function ($query, $terme) {
                $query->where(function ($q) use ($terme) {
                    $q->where('nom', 'like', "%{$terme}%")
                      ->orWhere('telephone', 'like', "%{$terme}%");
                });
            })
            ->orderBy('nom')
            ->get();

        return view('fournisseurs.index', compact('fournisseurs'));
    }

    public function create()
    {
        return view('fournisseurs.create', ['fournisseur' => new Fournisseur()]);
    }

    public function store(FournisseurRequest $request)
    {
        Fournisseur::create($request->validated());

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur ajouté.');
    }

    public function show(Fournisseur $fournisseur)
    {
        $achats = $fournisseur->achats()->latest('date_achat')->get();

        return view('fournisseurs.show', compact('fournisseur', 'achats'));
    }

    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    public function update(FournisseurRequest $request, Fournisseur $fournisseur)
    {
        $fournisseur->update($request->validated());

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur modifié.');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        try {
            $fournisseur->delete();
        } catch (QueryException $e) {
            return redirect()->route('fournisseurs.index')
                ->with('error', 'Impossible de supprimer : ce fournisseur a déjà des achats enregistrés.');
        }

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur supprimé.');
    }
}