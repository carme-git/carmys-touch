<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'       => ['required', 'string', 'max:100'],
            'prenom'    => ['nullable', 'string', 'max:100'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'email'     => ['nullable', 'email', 'max:150'],
            'adresse'   => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de la cliente est obligatoire.',
            'email.email'  => 'Cette adresse e-mail n\'est pas valide.',
        ];
    }
}