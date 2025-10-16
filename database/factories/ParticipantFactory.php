<?php

namespace Database\Factories;

use App\Models\Infos\Activity;
use App\Models\Users\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participant>
 */
class ParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'activity_id' => Activity::inRandomOrder()->firstOrFail()->id,
            'student_id' => Student::inRandomOrder()->firstOrFail()->id,
            //            'participantable_type' => $this->faker->randomElement(['student', 'teacher']),
            //            'participantable_id' => $this->faker->numberBetween(1, 40),
            //            'score'=> $this->faker->numberBetween(50,100),
        ];
    }
}
