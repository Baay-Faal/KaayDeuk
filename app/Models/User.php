<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\Role;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'prenom',
        'email',
        'password',
        'telephone',
        'adresse',
        'role',
        'photo',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Relations
     */

    // Un agent a plusieurs biens
    public function biens()
    {
        return $this->hasMany(Bien::class, 'agent_id');
    }

    // Un agent a plusieurs clients
    public function clients()
    {
        return $this->hasMany(Client::class, 'agent_id');
    }

    // Un agent gère plusieurs visites
    public function visites()
    {
        return $this->hasMany(Visite::class, 'agent_id');
    }

    // Un agent réalise plusieurs transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'agent_id');
    }

    /**
     * Accesseurs
     */

    // Nom complet de l'utilisateur
    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->name}";
    }

    /**
     * Scopes
     */

    // Filtrer par rôle
    public function scopeRole($query, Role $role)
    {
        return $query->where('role', $role);
    }

    // Uniquement les agents
    public function scopeAgents($query)
    {
        return $query->where('role', Role::AGENT);
    }

    // Uniquement les admins
    public function scopeAdmins($query)
    {
        return $query->where('role', Role::ADMIN);
    }

    // Uniquement les clients
    public function scopeClients($query)
    {
        return $query->where('role', Role::CLIENT);
    }

    // Utilisateurs actifs
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Méthodes de vérification de rôle
     */

    public function isAdmin(): bool
    {
        return $this->role === Role::ADMIN;
    }

    public function isAgent(): bool
    {
        return $this->role === Role::AGENT;
    }

    public function isClient(): bool
    {
        return $this->role === Role::CLIENT;
    }

    /**
     * Méthode pour enregistrer la dernière connexion
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }
}