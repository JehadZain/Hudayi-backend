<?php

namespace Database\Factories\Infos;

use App\Models\Properties\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $subjects = ['حديث', 'تفسير', 'سيرة', 'فقه', 'تجويد', 'ثقافي'];

        return [
            'name' => $this->faker->sentence(2),
            'description' => $this->faker->sentence(10),
            'property_id' => Property::inRandomOrder()->firstOrFail()->id,
        ];
    }
}
