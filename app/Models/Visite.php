<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\StatutVisite;

class Visite extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bien_id',
        'client_id',
        'agent_id',
        'date_visite',
        'heure_visite',
        'statut',
        'notes',
        'rapport',
        'note_client',
        'commentaire_client',
    ];

    protected function casts(): array
    {
        return [
            'date_visite' => 'date',
            'heure_visite' => 'datetime:H:i',
            'statut' => StatutVisite::class,
            'note_client' => 'integer',
        ];
    }

    /**
     * Relations
     */

    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Accesseurs
     */

    public function getDateHeureFormatAttribute(): string
    {
        return $this->date_visite->format('d/m/Y') . ' à ' . 
               $this->heure_visite->format('H:i');
    }

    /**
     * Scopes
     */

    public function scopeStatut($query, StatutVisite $statut)
    {
        return $query->where('statut', $statut);
    }

    public function scopePlanifiee($query)
    {
        return $query->where('statut', StatutVisite::PLANIFIEE);
    }

    public function scopeRealisee($query)
    {
        return $query->where('statut', StatutVisite::REALISEE);
    }

    public function scopeAnnulee($query)
    {
        return $query->where('statut', StatutVisite::ANNULEE);
    }

    public function scopeAVenir($query)
    {
        return $query->where('statut', StatutVisite::PLANIFIEE)
                     ->where('date_visite', '>=', now()->toDateString());
    }

    public function scopePassee($query)
    {
        return $query->where('date_visite', '<', now()->toDateString());
    }

    public function scopeParAgent($query, int $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    /**
     * Méthodes utilitaires
     */

    public function marquerRealisee(string $rapport = null): void
    {
        $this->update([
            'statut' => StatutVisite::REALISEE,
            'rapport' => $rapport,
        ]);
    }

    public function annuler(): void
    {
        $this->update(['statut' => StatutVisite::ANNULEE]);
    }

    public function estPlanifiee(): bool
    {
        return $this->statut === StatutVisite::PLANIFIEE;
    }

    public function estRealisee(): bool
    {
        return $this->statut === StatutVisite::REALISEE;
    }

    public function estAnnulee(): bool
    {
        return $this->statut === StatutVisite::ANNULEE;
    }
}