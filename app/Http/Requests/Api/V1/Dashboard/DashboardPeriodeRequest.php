<?php

namespace App\Http\Requests\Api\V1\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class DashboardPeriodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_debut' => ['nullable', 'date', 'date_format:Y-m-d'],
            'date_fin'   => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:date_debut'],
        ];
    }

    public function messages(): array
    {
        return [
            'date_debut.date'        => 'La date de début doit être une date valide.',
            'date_debut.date_format' => 'La date de début doit être au format AAAA-MM-JJ.',
            'date_fin.date'          => 'La date de fin doit être une date valide.',
            'date_fin.date_format'   => 'La date de fin doit être au format AAAA-MM-JJ.',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
        ];
    }

    /**
     * Prépare les données avant la validation.
     * Si aucune période fournie, on utilise le mois en cours par défaut.
     */
    protected function prepareForValidation(): void
    {
        // Pas de transformation nécessaire ici
        // Les valeurs par défaut sont gérées dans le controller
    }
}
