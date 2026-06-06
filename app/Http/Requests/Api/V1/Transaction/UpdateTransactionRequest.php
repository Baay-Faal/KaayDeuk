<?php

namespace App\Http\Requests\Api\V1\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
            'reference' => 'sometimes|string|unique:transactions,reference,' . $this->route('transaction')->id,
            'bien_id' => 'sometimes|exists:biens,id',
            'client_id' => 'sometimes|exists:clients,id',
            'type' => 'sometimes|in:vente,location',
            'montant' => 'sometimes|numeric|min:0',
            'commission_agence' => 'sometimes|numeric|min:0',
            'commission_agent' => 'sometimes|numeric|min:0',
            'date_signature' => 'sometimes|date',
            'date_debut_contrat' => 'sometimes|date',
            'date_fin_contrat' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'reference.unique' => 'Cette référence existe déjà.',
            'bien_id.exists' => 'Le bien sélectionné n\'existe pas.',
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
            'type.in' => 'Le type doit être: vente ou location.',
        ];
    }
}
