<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FournisseurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'       => ['required', 'string', 'max:150'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'email'     => ['nullable', 'email', 'max:150'],
            'adresse'   => ['nullable', 'string', 'max:191'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du fournisseur est obligatoire.',
            'email.email'  => "L'adresse e-mail n'est pas valide.",
        ];
    }
}