<?php

namespace Database\Factories\Properties;

use App\Models\Infos\Grade;
use App\Models\Properties\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassRoom>
 */
class ClassRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //            'property_id' => Property::inRandomOrder()->firstOrFail()->id,
            'name' => $this->faker->sentence(2),
            'capacity' => $this->faker->numberBetween(5, 50),
            'grade_id' => Grade::inRandomOrder()->firstOrFail()->id,

        ];
    }
}
