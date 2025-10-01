<?php

namespace Database\Factories;

use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
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
            'fantasy_name' => fake()->companySuffix().' '.fake()->company(),
            'cnpj' => fake()->unique()->numerify('##############'),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'street' => fake()->streetName(),
            'number' => (string) fake()->buildingNumber(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'zip_code' => fake()->postcode(),
            'is_active' => true,
            'creator_id' => null,
            'accountant_id' => null,
            'office_id' => Office::factory(),
        ];
    }
}
