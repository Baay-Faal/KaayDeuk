<?php

namespace App\Http\Requests\Api\V1\Visite;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisiteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isAdmin() || $this->user()->isAgent());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'bien_id' => 'required|exists:biens,id',
            'client_id' => 'required|exists:clients,id',
            'date_visite' => 'required|date|after:today',
            'heure_visite' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'bien_id.required' => 'Le bien est requis.',
            'bien_id.exists' => 'Le bien sélectionné n\'existe pas.',
            'client_id.required' => 'Le client est requis.',
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
            'date_visite.required' => 'La date de visite est requise.',
            'date_visite.date' => 'La date doit être une date valide.',
            'date_visite.after' => 'La date doit être après aujourd\'hui.',
            'heure_visite.required' => 'L\'heure de visite est requise.',
            'heure_visite.date_format' => 'L\'heure doit être au format HH:mm.',
        ];
    }
}
