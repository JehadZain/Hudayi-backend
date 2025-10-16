<?php

namespace Database\Factories\Infos;

use App\Models\Infos\Certification;
use App\Models\Infos\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Infos\CertificateTranscript>
 */
class CertificateTranscriptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $grade_name = $this->faker->randomElement(['A', 'B', 'C']);

        return [
            'certification_id' => Certification::inRandomOrder()->firstOrFail()->id,
            'subject_id' => Subject::inRandomOrder()->firstOrFail()->id,
            'max' => '100',
            'points' => $this->faker->randomFloat(1, 30, 500),
            'percentage' => $this->faker->numberBetween(0, 100),
            'grade_name' => $grade_name,
        ];
    }
}
