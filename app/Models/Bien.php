<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Enums\TypeBien;
use App\Enums\TypeTransaction;
use App\Enums\StatutBien;

class Bien extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'reference',
        'titre',
        'description',
        'type_bien',
        'type_transaction',
        'prix',
        'surface',
        'nombre_pieces',
        'nombre_chambres',
        'nombre_salles_bain',
        'adresse',
        'quartier',
        'ville',
        'latitude',
        'longitude',
        'statut',
        'agent_id',
        'caracteristiques',
        'annee_construction',
        'meuble',
        'climatise',
        'securise',
        'nombre_vues',
        'date_publication',
    ];

    protected function casts(): array
    {
        return [
            'type_bien' => TypeBien::class,
            'type_transaction' => TypeTransaction::class,
            'statut' => StatutBien::class,
            'prix' => 'decimal:2',
            'surface' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'caracteristiques' => 'array',
            'meuble' => 'boolean',
            'climatise' => 'boolean',
            'securise' => 'boolean',
            'date_publication' => 'datetime',
        ];
    }

    /**
     * Configuration Spatie Media Library
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos')
            ->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10);

        $this->addMediaConversion('medium')
            ->width(800)
            ->height(600)
            ->sharpen(10);
    }

    /**
     * Relations
     */

    // Un bien appartient à un agent
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    // Un bien a plusieurs visites
    public function visites()
    {
        return $this->hasMany(Visite::class);
    }

    // Un bien peut avoir plusieurs transactions (historique)
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Un bien peut être dans les favoris de plusieurs clients
    public function favoris()
    {
        return $this->hasMany(Favori::class);
    }

    // Clients qui ont mis ce bien en favoris
    public function clientsFavoris()
    {
        return $this->belongsToMany(Client::class, 'favoris');
    }

    /**
     * Accesseurs
     */

    public function getPrixFormatAttribute(): string
    {
        return number_format($this->prix, 0, ',', ' ') . ' FCFA';
    }

    public function getSurfaceFormatAttribute(): string
    {
        return number_format($this->surface, 2, ',', ' ') . ' m²';
    }

    /**
     * Scopes
     */

    // Filtrer par type de bien
    public function scopeTypeBien($query, TypeBien $type)
    {
        return $query->where('type_bien', $type);
    }

    // Filtrer par type de transaction
    public function scopeTypeTransaction($query, TypeTransaction $type)
    {
        return $query->where('type_transaction', $type);
    }

    // Filtrer par statut
    public function scopeStatut($query, StatutBien $statut)
    {
        return $query->where('statut', $statut);
    }

    // Biens disponibles
    public function scopeDisponible($query)
    {
        return $query->where('statut', StatutBien::DISPONIBLE);
    }

    // Biens d'une ville
    public function scopeVille($query, string $ville)
    {
        return $query->where('ville', 'LIKE', "%{$ville}%");
    }

    // Recherche par prix
    public function scopePrixEntre($query, ?float $min, ?float $max)
    {
        if ($min) {
            $query->where('prix', '>=', $min);
        }
        if ($max) {
            $query->where('prix', '<=', $max);
        }
        return $query;
    }

    // Recherche par surface
    public function scopeSurfaceEntre($query, ?float $min, ?float $max)
    {
        if ($min) {
            $query->where('surface', '>=', $min);
        }
        if ($max) {
            $query->where('surface', '<=', $max);
        }
        return $query;
    }

    // Recherche par proximité (géolocalisation)
    public function scopeProximite($query, float $latitude, float $longitude, float $rayon = 5)
    {
        // Formule Haversine pour calculer la distance
        $haversine = "(6371 * acos(cos(radians($latitude))
                     * cos(radians(latitude))
                     * cos(radians(longitude) - radians($longitude))
                     + sin(radians($latitude))
                     * sin(radians(latitude))))";

        return $query->selectRaw("{$haversine} AS distance")
                     ->whereNotNull('latitude')
                     ->whereNotNull('longitude')
                     ->having('distance', '<', $rayon)
                     ->orderBy('distance');
    }

    // Biens récents
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Biens populaires (plus de vues)
    public function scopePopulaire($query)
    {
        return $query->orderBy('nombre_vues', 'desc');
    }

    /**
     * Méthodes utilitaires
     */

    // Incrémenter le nombre de vues
    public function incrementVues(): void
    {
        $this->increment('nombre_vues');
    }

    // Changer le statut
    public function changerStatut(StatutBien $statut): void
    {
        $this->update(['statut' => $statut]);
    }

    // Vérifier si le bien est disponible
    public function estDisponible(): bool
    {
        return $this->statut === StatutBien::DISPONIBLE;
    }

    // Publier le bien
    public function publier(): void
    {
        $this->update([
            'statut' => StatutBien::DISPONIBLE,
            'date_publication' => now(),
        ]);
    }

    // Archiver le bien
    public function archiver(): void
    {
        $this->update(['statut' => StatutBien::ARCHIVE]);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement une référence unique
        static::creating(function ($bien) {
            if (empty($bien->reference)) {
                $bien->reference = 'BIEN-' . strtoupper(uniqid());
            }
        });
    }
}