<?php

namespace Database\Factories\Infos;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\statusType>
 */
class StatusTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type_name = $this->faker->randomElement(['admin', 'user', 'activity', 'exam']);

        return [
            'name' => $type_name,
            'description' => $this->faker->sentence(5),
        ];
    }
}
