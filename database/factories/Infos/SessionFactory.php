<?php

namespace Database\Factories\Infos;

use App\Models\Infos\Subject;
use App\Models\Properties\ClassRoom;
use App\Models\Users\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Infos\Session>
 */
class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'class_room_id' => ClassRoom::inRandomOrder()->firstOrFail()->id,
            'teacher_id' => Teacher::inRandomOrder()->firstOrFail()->id,
            'subject_id' => Subject::inRandomOrder()->firstOrFail()->id,
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'start_at' => $this->faker->dateTimeThisYear('now'),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(10),
            'duration' => $this->faker->numberBetween(15, 60),

        ];
    }
}
