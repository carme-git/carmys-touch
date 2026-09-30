<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Vente;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
{
    public function pdf(Vente $vente)
    {
        $facture = $this->obtenirOuCreer($vente);

        $vente->load(['client', 'detailsVentes.produit', 'paiements']);

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans'); // gère les accents
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('factures.pdf', compact('facture', 'vente'))->render());
        $dompdf->setPaper('A4');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $facture->numero_facture . '.pdf"',
        ]);
    }

    // Une vente a une seule facture : on la retrouve, ou on la crée avec le prochain numéro
    private function obtenirOuCreer(Vente $vente): Facture
    {
        return DB::transaction(function () use ($vente) {
            $existante = Facture::where('vente_id', $vente->id)->lockForUpdate()->first();
            if ($existante) {
                return $existante;
            }

            $annee   = now()->year;
            $dernier = Facture::where('numero_facture', 'like', "FAC-{$annee}-%")->max('numero_facture');
            $suite   = $dernier ? ((int) substr($dernier, -4)) + 1 : 1;

            return Facture::create([
                'vente_id'       => $vente->id,
                'numero_facture' => sprintf('FAC-%d-%04d', $annee, $suite),
                'date_facture'   => now()->toDateString(),
            ]);
        });
    }
}