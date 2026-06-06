<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->name,
            'prenom' => $this->prenom,
            'nom_complet' => $this->nom_complet,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'adresse' => $this->adresse,
            'role' => [
                'value' => $this->role->value,
                'label' => $this->role->label(),
            ],
            'photo' => $this->photo,
            'is_active' => $this->is_active,
            'email_verified_at' => $this->email_verified_at?->format('Y-m-d H:i:s'),
            'last_login_at' => $this->last_login_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            
            // Statistiques conditionnelles selon le rôle
            'statistiques' => $this->when($this->isAgent() || $this->isAdmin(), [
                'nombre_biens' => $this->when($this->isAgent(), $this->biens()->count()),
                'nombre_clients' => $this->when($this->isAgent(), $this->clients()->count()),
                'nombre_visites' => $this->when($this->isAgent(), $this->visites()->count()),
                'nombre_transactions' => $this->when($this->isAgent(), $this->transactions()->count()),
            ]),
        ];
    }
}