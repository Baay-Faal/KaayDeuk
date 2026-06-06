<?php

namespace App\Http\Requests\Api\V1\Bien;

use App\Enums\TypeBien;
use App\Enums\TypeTransaction;
use App\Enums\StatutBien;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBienRequest extends FormRequest
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
            'titre' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'type_bien' => ['sometimes', Rule::in(TypeBien::values())],
            'type_transaction' => ['sometimes', Rule::in(TypeTransaction::values())],
            'prix' => ['sometimes', 'numeric', 'min:0'],
            'surface' => ['sometimes', 'numeric', 'min:0'],
            'nombre_pieces' => ['nullable', 'integer', 'min:1'],
            'nombre_chambres' => ['nullable', 'integer', 'min:1'],
            'nombre_salles_bain' => ['nullable', 'integer', 'min:1'],
            'adresse' => ['sometimes', 'string', 'max:500'],
            'quartier' => ['sometimes', 'string', 'max:255'],
            'ville' => ['sometimes', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'statut' => ['sometimes', Rule::in(StatutBien::values())],
            'caracteristiques' => ['nullable', 'array'],
            'annee_construction' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'meuble' => ['nullable', 'boolean'],
            'climatise' => ['nullable', 'boolean'],
            'securise' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'titre.string' => 'Le titre doit être une chaîne de caractères.',
            'type_bien.in' => 'Le type de bien sélectionné est invalide.',
            'type_transaction.in' => 'Le type de transaction sélectionné est invalide.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix doit être supérieur ou égal à 0.',
            'surface.numeric' => 'La surface doit être un nombre.',
        ];
    }
}