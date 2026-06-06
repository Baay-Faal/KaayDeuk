<?php

namespace App\Http\Requests\Api\V1\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'reference' => 'required|string|unique:transactions,reference',
            'bien_id' => 'required|exists:biens,id',
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|in:vente,location',
            'montant' => 'required|numeric|min:0',
            'commission_agence' => 'required|numeric|min:0',
            'commission_agent' => 'required|numeric|min:0',
            'date_signature' => 'required|date',
            'date_debut_contrat' => 'required|date',
            'date_fin_contrat' => 'nullable|date|after:date_debut_contrat',
            'notes' => 'nullable|string|max:2000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'reference.required' => 'La référence est requise.',
            'reference.unique' => 'Cette référence existe déjà.',
            'bien_id.required' => 'Le bien est requis.',
            'bien_id.exists' => 'Le bien sélectionné n\'existe pas.',
            'client_id.required' => 'Le client est requis.',
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
            'type.required' => 'Le type est requis.',
            'type.in' => 'Le type doit être: vente ou location.',
            'montant.required' => 'Le montant est requis.',
            'date_signature.required' => 'La date de signature est requise.',
            'date_fin_contrat.after' => 'La date de fin doit être après la date de début.',
        ];
    }
}
