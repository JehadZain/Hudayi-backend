<?php

namespace Database\Factories\Infos;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Infos\rateType>
 */
class RateTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type = $this->faker->randomElement(['academic', 'accuracy', 'motivation']);

        return [
            'name' => $type,
            'description' => $this->faker->sentence(5),
        ];
    }
}
