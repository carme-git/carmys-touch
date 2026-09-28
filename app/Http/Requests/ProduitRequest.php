<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProduitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'    => ['nullable', 'exists:categories,id'],
            'nom'            => ['required', 'string', 'max:150'],
            'reference'      => [
                'nullable', 'string', 'max:50',
                Rule::unique('produits', 'reference')->ignore($this->route('produit')),
            ],
            'prix_achat'     => ['required', 'numeric', 'min:0'],
            'prix_vente'     => ['required', 'numeric', 'min:0'],
            'quantite_stock' => ['required', 'integer', 'min:0'],
            'seuil_alerte'   => ['required', 'integer', 'min:0'],
            'description'    => ['nullable', 'string'],
            'image'          => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required'            => 'Le nom du produit est obligatoire.',
            'reference.unique'        => 'Cette référence existe déjà. Choisis-en une autre.',
            'prix_achat.required'     => 'Le prix d\'achat est obligatoire.',
            'prix_vente.required'     => 'Le prix de vente est obligatoire.',
            'quantite_stock.required' => 'La quantité en stock est obligatoire.',
            'seuil_alerte.required'   => 'Le seuil d\'alerte est obligatoire.',
            'image.image'             => 'Le fichier doit être une image.',
            'image.max'               => 'L\'image ne doit pas dépasser 2 Mo.',
        ];
    }
}