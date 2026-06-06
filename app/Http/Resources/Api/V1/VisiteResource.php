<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisiteResource extends JsonResource
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
            'bien_id' => $this->bien_id,
            'bien' => new BienResource($this->whenLoaded('bien')),
            'client_id' => $this->client_id,
            'client' => new ClientResource($this->whenLoaded('client')),
            'agent_id' => $this->agent_id,
            'agent' => new UserResource($this->whenLoaded('agent')),
            'date_visite' => $this->date_visite->format('Y-m-d'),
            'heure_visite' => $this->heure_visite->format('H:i'),
            'date_heure_format' => $this->date_heure_format,
            'statut' => $this->statut->value,
            'statut_label' => $this->statut->label(),
            'notes' => $this->notes,
            'rapport' => $this->rapport,
            'note_client' => $this->note_client,
            'commentaire_client' => $this->commentaire_client,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
