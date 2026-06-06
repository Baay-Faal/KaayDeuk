<?php

namespace App\Http\Requests\Api\V1\Visite;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVisiteRequest extends FormRequest
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
            'bien_id' => 'sometimes|exists:biens,id',
            'client_id' => 'sometimes|exists:clients,id',
            'date_visite' => 'sometimes|date',
            'heure_visite' => 'sometimes|date_format:H:i',
            'statut' => 'sometimes|in:planifiee,realisee,annulee',
            'notes' => 'nullable|string|max:1000',
            'rapport' => 'nullable|string|max:2000',
            'note_client' => 'nullable|integer|min:0|max:5',
            'commentaire_client' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'bien_id.exists' => 'Le bien sélectionné n\'existe pas.',
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
            'date_visite.date' => 'La date doit être une date valide.',
            'heure_visite.date_format' => 'L\'heure doit être au format HH:mm.',
            'statut.in' => 'Le statut doit être: planifiée, réalisée ou annulée.',
            'note_client.min' => 'La note doit être entre 0 et 5.',
            'note_client.max' => 'La note doit être entre 0 et 5.',
        ];
    }
}
