<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'bien_id' => $this->bien_id,
            'bien' => new BienResource($this->whenLoaded('bien')),
            'client_id' => $this->client_id,
            'client' => new ClientResource($this->whenLoaded('client')),
            'agent_id' => $this->agent_id,
            'agent' => new UserResource($this->whenLoaded('agent')),
            'type' => $this->type->value,
            'type_label' => $this->type->label(),
            'montant' => (float)$this->montant,
            'montant_format' => number_format($this->montant, 0, ',', ' ') . ' FCFA',
            'commission_agence' => (float)$this->commission_agence,
            'commission_agence_format' => number_format($this->commission_agence, 0, ',', ' ') . ' FCFA',
            'commission_agent' => (float)$this->commission_agent,
            'date_signature' => $this->date_signature->format('Y-m-d'),
            'date_debut_contrat' => $this->date_debut_contrat->format('Y-m-d'),
            'date_fin_contrat' => $this->date_fin_contrat?->format('Y-m-d'),
            'notes' => $this->notes,
            'contrat_path' => $this->contrat_path,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
