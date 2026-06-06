<?php

namespace App\Http\Requests\Api\V1\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:clients,email'],
            'telephone' => ['required', 'string', 'regex:/^(77|78|76|70|75)[0-9]{7}$/', 'unique:clients,telephone'],
            'adresse' => ['nullable', 'string', 'max:500'],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0', 'gte:budget_min'],
            'preferences' => ['nullable', 'array'],
            'preferences.type_bien' => ['nullable', 'string'],
            'preferences.quartiers' => ['nullable', 'array'],
            'preferences.nombre_chambres_min' => ['nullable', 'integer', 'min:1'],
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
            'nom.required' => 'Le nom est requis.',
            'prenom.required' => 'Le prénom est requis.',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'email doit être une adresse valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'telephone.required' => 'Le téléphone est requis.',
            'telephone.regex' => 'Le numéro de téléphone doit être un numéro sénégalais valide (77/78/76/70/75).',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'budget_max.gte' => 'Le budget maximum doit être supérieur ou égal au budget minimum.',
        ];
    }
}