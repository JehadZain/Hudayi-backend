<?php

namespace Database\Factories;

use App\Models\Infos\Session;
use App\Models\Users\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SessionAttendance>
 */
class SessionAttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'session_id' => Session::inRandomOrder()->firstOrFail()->id,
            'student_id' => Student::inRandomOrder()->firstOrFail()->id,
            //            'joined_at'=>$this->faker->dateTime('now'),
            //            'left_at'=>$this->faker->dateTime('now'),
        ];
    }
}
