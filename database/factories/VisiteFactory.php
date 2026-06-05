<?php

namespace Database\Factories;

use App\Models\Bien;
use App\Models\Client;
use App\Models\User;
use App\Enums\StatutVisite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visite>
 */
class VisiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statut = fake()->randomElement(StatutVisite::cases());
        
        // Date de visite (peut être passée ou future)
        $dateVisite = fake()->dateTimeBetween('-30 days', '+30 days');
        
        $rapport = null;
        $noteClient = null;
        $commentaireClient = null;

        // Si la visite est réalisée, ajouter un rapport et une note
        if ($statut === StatutVisite::REALISEE) {
            $rapport = fake()->paragraphs(2, true);
            $noteClient = fake()->numberBetween(1, 5);
            $commentaireClient = fake()->optional(0.7)->sentence();
        }

        return [
            'bien_id' => Bien::factory(),
            'client_id' => Client::factory(),
            'agent_id' => User::factory(),
            'date_visite' => $dateVisite,
            'heure_visite' => fake()->time('H:i:s'),
            'statut' => $statut,
            'notes' => fake()->optional()->sentence(),
            'rapport' => $rapport,
            'note_client' => $noteClient,
            'commentaire_client' => $commentaireClient,
        ];
    }

    /**
     * Visite planifiée
     */
    public function planifiee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => StatutVisite::PLANIFIEE,
            'date_visite' => fake()->dateTimeBetween('now', '+30 days'),
            'rapport' => null,
            'note_client' => null,
        ]);
    }

    /**
     * Visite réalisée
     */
    public function realisee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => StatutVisite::REALISEE,
            'date_visite' => fake()->dateTimeBetween('-30 days', 'now'),
            'rapport' => fake()->paragraphs(2, true),
            'note_client' => fake()->numberBetween(3, 5),
            'commentaire_client' => fake()->sentence(),
        ]);
    }

    /**
     * Visite annulée
     */
    public function annulee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => StatutVisite::ANNULEE,
            'rapport' => null,
            'note_client' => null,
        ]);
    }
}