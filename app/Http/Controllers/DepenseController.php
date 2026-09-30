<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepenseRequest;
use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepenseController extends Controller
{
    public function index(Request $request)
    {
        // Mois affiché au format AAAA-MM ; toute valeur invalide retombe sur le mois en cours
        $mois = $request->input('mois');
        if (! is_string($mois) || ! preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $mois)) {
            $mois = now()->format('Y-m');
        }
        [$annee, $numeroMois] = explode('-', $mois);

        $requete = Depense::whereYear('date_depense', $annee)->whereMonth('date_depense', $numeroMois);

        $total    = (clone $requete)->sum('montant');
        $depenses = $requete->orderByDesc('date_depense')->orderByDesc('id')->get();

        return view('depenses.index', compact('depenses', 'total', 'mois'));
    }

    public function create()
    {
        return view('depenses.create', ['depense' => new Depense()]);
    }

    public function store(DepenseRequest $request)
    {
        Depense::create($request->validated() + ['utilisateur_id' => Auth::id()]);

        return redirect()->route('depenses.index')->with('success', 'Dépense enregistrée.');
    }

    public function edit(Depense $depense)
    {
        return view('depenses.edit', compact('depense'));
    }

    public function update(DepenseRequest $request, Depense $depense)
    {
        $depense->update($request->validated());

        return redirect()->route('depenses.index')->with('success', 'Dépense modifiée.');
    }

    public function destroy(Depense $depense)
    {
        $depense->delete();

        return redirect()->route('depenses.index')->with('success', 'Dépense supprimée.');
    }
}