<?php

namespace Database\Factories;

use App\Models\Bien;
use App\Models\Client;
use App\Models\User;
use App\Enums\TypeTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(TypeTransaction::cases());
        $montant = fake()->numberBetween(15000000, 100000000);
        
        // Commission agence (5% du montant)
        $commissionAgence = $montant * 0.05;
        
        // Commission agent (50% de la commission agence)
        $commissionAgent = $commissionAgence * 0.5;

        $dateSignature = fake()->dateTimeBetween('-6 months', 'now');
        
        $dateDebutContrat = null;
        $dateFinContrat = null;

        // Si c'est une location, définir les dates de contrat
        if ($type === TypeTransaction::LOCATION) {
            $dateDebutContrat = $dateSignature;
            $dateFinContrat = (clone $dateDebutContrat)->modify('+1 year');
        }

        return [
            'reference' => 'TRX-' . strtoupper(Str::random(10)),
            'bien_id' => Bien::factory(),
            'client_id' => Client::factory(),
            'agent_id' => User::factory(),
            'type' => $type,
            'montant' => $montant,
            'commission_agence' => $commissionAgence,
            'commission_agent' => $commissionAgent,
            'date_signature' => $dateSignature,
            'date_debut_contrat' => $dateDebutContrat,
            'date_fin_contrat' => $dateFinContrat,
            'notes' => fake()->optional()->paragraph(),
            'contrat_path' => null, // Sera généré plus tard avec PDF
        ];
    }

    /**
     * Transaction de vente
     */
    public function vente(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TypeTransaction::VENTE,
            'date_debut_contrat' => null,
            'date_fin_contrat' => null,
        ]);
    }

    /**
     * Transaction de location
     */
    public function location(): static
    {
        $dateDebut = fake()->dateTimeBetween('-6 months', 'now');
        
        return $this->state(fn (array $attributes) => [
            'type' => TypeTransaction::LOCATION,
            'date_debut_contrat' => $dateDebut,
            'date_fin_contrat' => (clone $dateDebut)->modify('+1 year'),
        ]);
    }
}