<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id'               => ['nullable', 'exists:clients,id'],
            'client_nom'              => ['nullable', 'string', 'max:100'],
            'client_telephone'        => ['nullable', 'string', 'max:30'],
            'date_vente'              => ['required', 'date'],
            'mode_livraison'          => ['required', 'in:soi_meme,service'],
            'frais_livraison'         => ['nullable', 'numeric', 'min:0'],
            'lignes'                  => ['required', 'array', 'min:1'],
            'lignes.*.produit_id'     => ['required', 'exists:produits,id'],
            'lignes.*.quantite'       => ['required', 'integer', 'min:1'],
            'lignes.*.prix_unitaire'  => ['required', 'numeric', 'min:0'],
            'lignes.*.remise'         => ['nullable', 'numeric', 'min:0'],
                        'paye_maintenant'         => ['nullable', 'boolean'],
            'mode_paiement_immediat'  => ['nullable', 'in:especes,mobile_money'],
        ];
    }

    public function messages(): array
    {
        return [
            'lignes.required'             => 'Ajoute au moins un parfum à la vente.',
            'lignes.min'                  => 'Ajoute au moins un parfum à la vente.',
            'lignes.*.quantite.min'       => 'La quantité doit être au moins 1.',
            'lignes.*.produit_id.exists'  => "Ce produit n'existe pas.",
        ];
    }
}