<?php

namespace App\Http\Requests\Api\V1\Bien;

use Illuminate\Foundation\Http\FormRequest;

class UploadPhotoRequest extends FormRequest
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
            'photos' => ['required', 'array', 'min:1', 'max:10'],
            'photos.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // 5MB max
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'photos.required' => 'Au moins une photo est requise.',
            'photos.array' => 'Les photos doivent être un tableau.',
            'photos.min' => 'Au moins une photo est requise.',
            'photos.max' => 'Vous ne pouvez uploader que 10 photos maximum.',
            'photos.*.required' => 'Chaque photo est requise.',
            'photos.*.image' => 'Le fichier doit être une image.',
            'photos.*.mimes' => 'Les formats acceptés sont : jpeg, png, jpg, webp.',
            'photos.*.max' => 'Chaque photo ne doit pas dépasser 5 Mo.',
        ];
    }
}