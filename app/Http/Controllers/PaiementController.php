<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaiementController extends Controller
{
    public function store(Request $request, Vente $vente)
    {
        $reste = $vente->reste_a_payer;

        if ($reste <= 0) {
            return back()->with('error', 'Cette vente est déjà entièrement payée.');
        }

        $data = $request->validate([
            'montant'       => ['required', 'numeric', 'min:1', 'max:' . $reste],
            'mode_paiement' => ['required', 'in:especes,mobile_money'],
            'date_paiement' => ['required', 'date'],
            'reference'     => ['nullable', 'string', 'max:100'],
        ], [
            'montant.max' => 'Le montant dépasse le reste à payer (' . number_format($reste, 0, ',', ' ') . ' F).',
            'montant.min' => 'Le montant doit être supérieur à 0.',
        ]);

        DB::transaction(function () use ($vente, $data) {
            $vente->paiements()->create($data);
            $vente->recalculerStatut();
        });

        return back()->with('success', 'Paiement enregistré.');
    }

    public function destroy(Paiement $paiement)
    {
        $vente = $paiement->vente;

        DB::transaction(function () use ($paiement, $vente) {
            $paiement->delete();
            $vente->recalculerStatut();
        });

        return back()->with('success', 'Paiement supprimé.');
    }
}