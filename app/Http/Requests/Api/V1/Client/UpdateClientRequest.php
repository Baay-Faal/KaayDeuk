<?php

namespace App\Http\Requests\Api\V1\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $clientId = $this->route('client')->id;

        return [
            'nom' => ['sometimes', 'string', 'max:255'],
            'prenom' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:clients,email,' . $clientId],
            'telephone' => ['sometimes', 'string', 'regex:/^(77|78|76|70|75)[0-9]{7}$/', 'unique:clients,telephone,' . $clientId],
            'adresse' => ['nullable', 'string', 'max:500'],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0', 'gte:budget_min'],
            'preferences' => ['nullable', 'array'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.email' => 'L\'email doit être une adresse valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'telephone.regex' => 'Le numéro de téléphone doit être un numéro sénégalais valide.',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'budget_max.gte' => 'Le budget maximum doit être supérieur ou égal au budget minimum.',
        ];
    }
}