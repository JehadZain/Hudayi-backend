<?php

namespace Database\Factories\Infos;

use App\Models\ActivityType;
use App\Models\Properties\ClassRoom;
use App\Models\Users\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Infos\Activity>
 */
class ActivityFactory extends Factory
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
            'activity_type_id' => ActivityType::inRandomOrder()->firstOrFail()->id,
            'teacher_id' => Teacher::inRandomOrder()->firstOrFail()->id,
            'name' => $this->faker->randomFloat(['نشاط كرة', 'نشاط فطور', 'نشاط مسير', 'نشاط زيارة', 'نشاط سباحة']),
            'place' => $this->faker->randomElement(['عفرين', 'إدلب', 'إعزاز', 'الباب', 'جنديرس']),
            'cost' => $this->faker->numberBetween(30, 500),
            'result' => $this->faker->sentence(3),
            'note' => $this->faker->sentence(15),
            'start_datetime' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'end_datetime' => $this->faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
