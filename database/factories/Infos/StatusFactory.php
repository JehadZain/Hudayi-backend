<?php

namespace Database\Factories\Infos;

use App\Models\Infos\StatusType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Infos\Status>
 */
class StatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $status_name = $this->faker->randomElement(['pending', 'passive', 'active']);

        return [
            'name' => $status_name,
            'status_type_id' => StatusType::inRandomOrder()->firstOrFail()->id,
        ];
    }
}
