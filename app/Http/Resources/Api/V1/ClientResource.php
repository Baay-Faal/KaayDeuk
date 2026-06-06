<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'nom_complet' => $this->nom_complet,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'adresse' => $this->adresse,
            'budget' => [
                'min' => $this->budget_min,
                'max' => $this->budget_max,
                'format' => $this->budget_format,
            ],
            'preferences' => $this->preferences,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            
            // Agent responsable
            'agent' => [
                'id' => $this->agent->id,
                'nom_complet' => $this->agent->nom_complet,
                'telephone' => $this->agent->telephone,
                'email' => $this->agent->email,
            ],
            
            // Statistiques
            'statistiques' => $this->when($request->has('with_stats'), [
                'nombre_visites' => $this->visites()->count(),
                'nombre_transactions' => $this->transactions()->count(),
                'nombre_favoris' => $this->favoris()->count(),
            ]),
            
            // Favoris (si demandé)
            'favoris' => $this->when($request->has('with_favoris'), 
                BienResource::collection($this->biensFavoris)
            ),
            
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}