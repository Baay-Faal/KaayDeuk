<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\TypeTransaction;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference',
        'bien_id',
        'client_id',
        'agent_id',
        'type',
        'montant',
        'commission_agence',
        'commission_agent',
        'date_signature',
        'date_debut_contrat',
        'date_fin_contrat',
        'notes',
        'contrat_path',
    ];

    protected function casts(): array
    {
        return [
            'type' => TypeTransaction::class,
            'montant' => 'decimal:2',
            'commission_agence' => 'decimal:2',
            'commission_agent' => 'decimal:2',
            'date_signature' => 'date',
            'date_debut_contrat' => 'date',
            'date_fin_contrat' => 'date',
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

    public function getMontantFormatAttribute(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }

    public function getCommissionAgenceFormatAttribute(): string
    {
        return number_format($this->commission_agence, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Scopes
     */

    public function scopeType($query, TypeTransaction $type)
    {
        return $query->where('type', $type);
    }

    public function scopeVente($query)
    {
        return $query->where('type', TypeTransaction::VENTE);
    }

    public function scopeLocation($query)
    {
        return $query->where('type', TypeTransaction::LOCATION);
    }

    public function scopeParAgent($query, int $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    public function scopeParPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_signature', [$dateDebut, $dateFin]);
    }

    /**
     * Méthodes utilitaires
     */

    public function estVente(): bool
    {
        return $this->type === TypeTransaction::VENTE;
    }

    public function estLocation(): bool
    {
        return $this->type === TypeTransaction::LOCATION;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->reference)) {
                $transaction->reference = 'TRX-' . strtoupper(uniqid());
            }
        });
    }
}