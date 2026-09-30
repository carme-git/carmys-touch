<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AchatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'                    => ['required', 'in:commande_client,stock_avance'],
            'fournisseur_id'          => ['nullable', 'exists:fournisseurs,id'],
            'vente_id'                => ['nullable', 'exists:ventes,id'],
            'date_achat'              => ['required', 'date'],
            'lignes'                  => ['required', 'array', 'min:1'],
            'lignes.*.produit_id'     => ['required', 'exists:produits,id'],
            'lignes.*.quantite'       => ['required', 'integer', 'min:1'],
            'lignes.*.prix_unitaire'  => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'lignes.required'            => 'Ajoute au moins un parfum à l\'achat.',
            'lignes.min'                 => 'Ajoute au moins un parfum à l\'achat.',
            'lignes.*.quantite.min'      => 'La quantité doit être au moins 1.',
            'lignes.*.produit_id.exists' => "Ce produit n'existe pas.",
        ];
    }
}