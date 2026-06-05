<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\TypeBien;
use App\Enums\TypeTransaction;
use App\Enums\StatutBien;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bien>
 */
class BienFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $typeBien = fake()->randomElement(TypeBien::cases());
        $typeTransaction = fake()->randomElement(TypeTransaction::cases());
        
        // Prix selon le type de bien et transaction
        $prix = match($typeBien) {
            TypeBien::APPARTEMENT => fake()->numberBetween(15000000, 50000000),
            TypeBien::VILLA => fake()->numberBetween(40000000, 150000000),
            TypeBien::BUREAU => fake()->numberBetween(20000000, 80000000),
            TypeBien::TERRAIN => fake()->numberBetween(5000000, 100000000),
            TypeBien::COMMERCE => fake()->numberBetween(25000000, 100000000),
        };

        // Ajuster le prix si c'est une location
        if ($typeTransaction === TypeTransaction::LOCATION) {
            $prix = $prix * 0.01; // 1% du prix de vente
        }

        $surface = fake()->numberBetween(50, 500);
        $nombrePieces = $typeBien === TypeBien::TERRAIN ? null : fake()->numberBetween(2, 8);
        $nombreChambres = $typeBien === TypeBien::TERRAIN ? null : fake()->numberBetween(1, 6);
        $nombreSallesBain = $typeBien === TypeBien::TERRAIN ? null : fake()->numberBetween(1, 4);

        // Villes du Sénégal
        $villes = ['Dakar', 'Thiès', 'Saint-Louis', 'Rufisque', 'Kaolack', 'Ziguinchor', 'Louga', 'Mbour'];
        $ville = fake()->randomElement($villes);

        // Quartiers par ville
        $quartiersPossibles = match($ville) {
            'Dakar' => ['Plateau', 'Almadies', 'Mermoz', 'Sacré-Coeur', 'Point E', 'Ouakam', 'Fann', 'HLM'],
            'Thiès' => ['Escale', 'Médina', 'Randoulène', 'Mbour 1'],
            'Saint-Louis' => ['Sor', 'Île de Saint-Louis', 'Ndioloffène'],
            default => ['Centre-ville', 'Zone résidentielle', 'Périphérie'],
        };

        $quartier = fake()->randomElement($quartiersPossibles);

        // Coordonnées GPS (Dakar comme référence)
        $latitude = fake()->latitude(14.6, 14.8);
        $longitude = fake()->longitude(-17.5, -17.3);

        return [
            'reference' => 'BIEN-' . strtoupper(Str::random(8)),
            'titre' => $this->genererTitre($typeBien, $typeTransaction, $quartier),
            'description' => fake()->paragraphs(3, true),
            'type_bien' => $typeBien,
            'type_transaction' => $typeTransaction,
            'prix' => $prix,
            'surface' => $surface,
            'nombre_pieces' => $nombrePieces,
            'nombre_chambres' => $nombreChambres,
            'nombre_salles_bain' => $nombreSallesBain,
            'adresse' => fake()->streetAddress(),
            'quartier' => $quartier,
            'ville' => $ville,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'statut' => fake()->randomElement([
                StatutBien::DISPONIBLE,
                StatutBien::DISPONIBLE,
                StatutBien::DISPONIBLE, // Plus de biens disponibles
                StatutBien::RESERVE,
                StatutBien::VENDU,
            ]),
            'agent_id' => User::factory(),
            'caracteristiques' => [
                'parking' => fake()->boolean(70),
                'jardin' => fake()->boolean(50),
                'piscine' => fake()->boolean(20),
                'ascenseur' => fake()->boolean(40),
                'balcon' => fake()->boolean(60),
            ],
            'annee_construction' => fake()->numberBetween(2000, 2024),
            'meuble' => fake()->boolean(30),
            'climatise' => fake()->boolean(70),
            'securise' => fake()->boolean(80),
            'nombre_vues' => fake()->numberBetween(0, 500),
            'date_publication' => now()->subDays(fake()->numberBetween(1, 90)),
        ];
    }

    /**
     * Générer un titre attractif pour le bien
     */
    private function genererTitre(TypeBien $type, TypeTransaction $transaction, string $quartier): string
    {
        $action = $transaction === TypeTransaction::VENTE ? 'À vendre' : 'À louer';
        
        return match($type) {
            TypeBien::APPARTEMENT => "{$action} - Bel appartement à {$quartier}",
            TypeBien::VILLA => "{$action} - Magnifique villa à {$quartier}",
            TypeBien::BUREAU => "{$action} - Bureau moderne à {$quartier}",
            TypeBien::TERRAIN => "{$action} - Terrain viabilisé à {$quartier}",
            TypeBien::COMMERCE => "{$action} - Local commercial à {$quartier}",
        };
    }

    /**
     * Bien disponible
     */
    public function disponible(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => StatutBien::DISPONIBLE,
        ]);
    }

    /**
     * Bien vendu
     */
    public function vendu(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => StatutBien::VENDU,
            'type_transaction' => TypeTransaction::VENTE,
        ]);
    }

    /**
     * Bien loué
     */
    public function loue(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => StatutBien::LOUE,
            'type_transaction' => TypeTransaction::LOCATION,
        ]);
    }

    /**
     * Villa
     */
    public function villa(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_bien' => TypeBien::VILLA,
            'prix' => fake()->numberBetween(50000000, 150000000),
            'surface' => fake()->numberBetween(200, 500),
            'nombre_chambres' => fake()->numberBetween(4, 8),
        ]);
    }

    /**
     * Appartement
     */
    public function appartement(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_bien' => TypeBien::APPARTEMENT,
            'prix' => fake()->numberBetween(15000000, 50000000),
            'surface' => fake()->numberBetween(50, 150),
            'nombre_chambres' => fake()->numberBetween(1, 4),
        ]);
    }
}