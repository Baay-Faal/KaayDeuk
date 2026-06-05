<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'budget_min',
        'budget_max',
        'preferences',
        'agent_id',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
            'preferences' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relations
     */

    // Un client appartient à un agent
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    // Un client a plusieurs visites
    public function visites()
    {
        return $this->hasMany(Visite::class);
    }

    // Un client a plusieurs transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Un client a plusieurs favoris
    public function favoris()
    {
        return $this->hasMany(Favori::class);
    }

    // Biens favoris du client
    public function biensFavoris()
    {
        return $this->belongsToMany(Bien::class, 'favoris');
    }

    /**
     * Accesseurs
     */

    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function getBudgetFormatAttribute(): string
    {
        if ($this->budget_min && $this->budget_max) {
            return number_format($this->budget_min, 0, ',', ' ') . ' - ' . 
                   number_format($this->budget_max, 0, ',', ' ') . ' FCFA';
        }
        return 'Non défini';
    }

    /**
     * Scopes
     */

    // Clients actifs
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Clients d'un agent spécifique
    public function scopeParAgent($query, int $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    // Clients avec un budget spécifique
    public function scopeBudgetEntre($query, ?float $min, ?float $max)
    {
        if ($min) {
            $query->where('budget_max', '>=', $min);
        }
        if ($max) {
            $query->where('budget_min', '<=', $max);
        }
        return $query;
    }

    /**
     * Méthodes utilitaires
     */

    // Ajouter un bien aux favoris
    public function ajouterFavori(Bien $bien): void
    {
        if (!$this->aFavori($bien)) {
            $this->favoris()->create(['bien_id' => $bien->id]);
        }
    }

    // Retirer un bien des favoris
    public function retirerFavori(Bien $bien): void
    {
        $this->favoris()->where('bien_id', $bien->id)->delete();
    }

    // Vérifier si un bien est en favoris
    public function aFavori(Bien $bien): bool
    {
        return $this->favoris()->where('bien_id', $bien->id)->exists();
    }

    // Activer/désactiver le client
    public function activer(): void
    {
        $this->update(['is_active' => true]);
    }

    public function desactiver(): void
    {
        $this->update(['is_active' => false]);
    }
}