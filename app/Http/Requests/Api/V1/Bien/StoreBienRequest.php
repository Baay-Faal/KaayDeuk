<?php

namespace App\Http\Requests\Api\V1\Bien;

use App\Enums\TypeBien;
use App\Enums\TypeTransaction;
use App\Enums\StatutBien;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBienRequest extends FormRequest
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
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'type_bien' => ['required', Rule::in(TypeBien::values())],
            'type_transaction' => ['required', Rule::in(TypeTransaction::values())],
            'prix' => ['required', 'numeric', 'min:0'],
            'surface' => ['required', 'numeric', 'min:0'],
            'nombre_pieces' => ['nullable', 'integer', 'min:1'],
            'nombre_chambres' => ['nullable', 'integer', 'min:1'],
            'nombre_salles_bain' => ['nullable', 'integer', 'min:1'],
            'adresse' => ['required', 'string', 'max:500'],
            'quartier' => ['required', 'string', 'max:255'],
            'ville' => ['required', 'string', 'max:255'],
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
            'titre.required' => 'Le titre est requis.',
            'description.required' => 'La description est requise.',
            'type_bien.required' => 'Le type de bien est requis.',
            'type_bien.in' => 'Le type de bien sélectionné est invalide.',
            'type_transaction.required' => 'Le type de transaction est requis.',
            'type_transaction.in' => 'Le type de transaction sélectionné est invalide.',
            'prix.required' => 'Le prix est requis.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix doit être supérieur ou égal à 0.',
            'surface.required' => 'La surface est requise.',
            'surface.numeric' => 'La surface doit être un nombre.',
            'adresse.required' => 'L\'adresse est requise.',
            'quartier.required' => 'Le quartier est requis.',
            'ville.required' => 'La ville est requise.',
            'latitude.between' => 'La latitude doit être entre -90 et 90.',
            'longitude.between' => 'La longitude doit être entre -180 et 180.',
        ];
    }
}