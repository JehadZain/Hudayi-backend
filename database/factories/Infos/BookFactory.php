<?php

namespace Database\Factories\Infos;

use App\Models\Infos\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Infos\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'size' => $this->faker->randomElement(['A3', 'A4']),
            'paper_count' => $this->faker->numberBetween(30, 500),
            'author_name' => $this->faker->name(),
            'subject_id' => Subject::inRandomOrder()->firstOrFail()->id,
        ];
    }
}
