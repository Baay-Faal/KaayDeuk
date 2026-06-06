<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BienResource extends JsonResource
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
            'reference' => $this->reference,
            'titre' => $this->titre,
            'description' => $this->description,
            'type_bien' => [
                'value' => $this->type_bien->value,
                'label' => $this->type_bien->label(),
            ],
            'type_transaction' => [
                'value' => $this->type_transaction->value,
                'label' => $this->type_transaction->label(),
            ],
            'prix' => $this->prix,
            'prix_format' => $this->prix_format,
            'surface' => $this->surface,
            'surface_format' => $this->surface_format,
            'nombre_pieces' => $this->nombre_pieces,
            'nombre_chambres' => $this->nombre_chambres,
            'nombre_salles_bain' => $this->nombre_salles_bain,
            'adresse' => $this->adresse,
            'quartier' => $this->quartier,
            'ville' => $this->ville,
            'localisation' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],
            'statut' => [
                'value' => $this->statut->value,
                'label' => $this->statut->label(),
                'color' => $this->statut->color(),
            ],
            'caracteristiques' => $this->caracteristiques,
            'annee_construction' => $this->annee_construction,
            'meuble' => $this->meuble,
            'climatise' => $this->climatise,
            'securise' => $this->securise,
            'nombre_vues' => $this->nombre_vues,
            'date_publication' => $this->date_publication?->format('Y-m-d H:i:s'),
            
            // Agent responsable
            'agent' => [
                'id' => $this->agent->id,
                'nom_complet' => $this->agent->nom_complet,
                'telephone' => $this->agent->telephone,
                'email' => $this->agent->email,
            ],
            
            // Photos
            'photos' => $this->getMedia('photos')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'thumb' => $media->getUrl('thumb'),
                    'medium' => $media->getUrl('medium'),
                ];
            }),
            
            // Statistiques (si demandé)
            'statistiques' => $this->when($request->has('with_stats'), [
                'nombre_visites' => $this->visites()->count(),
                'nombre_favoris' => $this->favoris()->count(),
            ]),
            
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}