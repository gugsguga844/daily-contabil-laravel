<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Office>
 */
class OfficeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'fantasy_name' => fake()->companySuffix() . ' ' . fake()->company(),
            'cnpj' => fake()->unique()->numerify('##############'),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'street' => fake()->streetName(),
            'number' => (string) fake()->buildingNumber(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'zip_code' => fake()->postcode(),
            'is_active' => true,
            'office_owner_id' => null,
            'current_plan' => fake()->randomElement(['free', 'basic', 'premium']),
        ];
    }

    /**
     * Estado auxiliar para vincular um owner específico quando necessário.
     */
    public function withOwner(?User $user = null): static
    {
        return $this->state(function () use ($user) {
            return [
                'office_owner_id' => $user?->id ?? User::factory(),
            ];
        });
    }
}
