<?php

namespace Database\Factories\Properties;

use App\Models\Properties\ClassRoom;
use App\Models\Users\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Properties\ClassRoomStudent>
 */
class ClassRoomStudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //            'class_room_id' => ClassRoom::inRandomOrder()->firstOrFail()->id,
            //            'student_id' => Student::inRandomOrder()->firstOrFail()->id,
            //            'joined_at' => $this->faker->dateTimeBetween('-1 years', 'now'),
            //            'left_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
