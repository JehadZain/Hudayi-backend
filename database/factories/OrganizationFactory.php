<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            //            'logo' => '',
            'location' => $this->faker->country,
            'capacity' => $this->faker->numberBetween(100, 200),
            'website' => $this->faker->url,
            'description' => $this->faker->sentence('20'),
        ];
    }
}
