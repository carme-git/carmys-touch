<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categorie'    => ['required', 'string', 'max:100'],
            'montant'      => ['required', 'numeric', 'min:1'],
            'date_depense' => ['required', 'date'],
            'description'  => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'categorie.required' => 'Indique la catégorie de la dépense.',
            'montant.required'   => 'Le montant est obligatoire.',
            'montant.min'        => 'Le montant doit être supérieur à 0.',
        ];
    }
}