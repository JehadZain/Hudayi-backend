<?php

namespace Database\Factories\Properties;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => '',
            'capacity' => $this->faker->numberBetween(30, 100),
            'property_type' => $this->faker->randomElement(['school', 'mosque']),
            'description' => $this->faker->sentence(8),
            'branch_id' => '',
        ];
    }
}
