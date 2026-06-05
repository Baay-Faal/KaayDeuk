<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $budgetMin = fake()->numberBetween(10000000, 50000000);
        $budgetMax = $budgetMin + fake()->numberBetween(10000000, 50000000);

        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'telephone' => '77' . fake()->numberBetween(1000000, 9999999),
            'adresse' => fake()->address(),
            'budget_min' => $budgetMin,
            'budget_max' => $budgetMax,
            'preferences' => [
                'type_bien' => fake()->randomElement(['appartement', 'villa', 'bureau']),
                'quartiers' => fake()->randomElements(['Plateau', 'Almadies', 'Mermoz', 'Sacré-Coeur'], 2),
                'nombre_chambres_min' => fake()->numberBetween(2, 4),
            ],
            'agent_id' => User::factory(),
            'notes' => fake()->optional()->sentence(),
            'is_active' => fake()->boolean(90), // 90% actifs
        ];
    }

    /**
     * Client actif
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Client inactif
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}