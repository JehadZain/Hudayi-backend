<?php

namespace Database\Factories\Infos;

use App\Models\Infos\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PatientTreatment>
 */
class PatientTreatmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'patient_id' => Patient::inRandomOrder()->firstOrFail()->id,
            'treatment_name' => $this->faker->sentence('2'),
            'treatment_cost' => $this->faker->numberBetween(25, 5000),
            'availability' => $this->faker->randomElement(['easy_to_find_locally', 'hard_to_find_locally', 'not_available_locally']),
            'schedule' => $this->faker->randomElement(['daily', 'twice_a_week', 'weekly', 'monthly']),
        ];
    }
}
