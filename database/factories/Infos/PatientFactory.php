<?php

namespace Database\Factories\Infos;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->firstOrFail()->id,
            'disease_name' => $this->faker->sentence(2),
            'diagnosis_date' => $this->faker->dateTimeBetween('-10years', 'now'),
            //            'medical_report_url' => '',
            //            'medical_report_image' => ''
        ];
    }
}
