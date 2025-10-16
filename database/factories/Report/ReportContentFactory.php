<?php

namespace Database\Factories\Report;

use App\Models\Morphs\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report\ReportContent>
 */
class ReportContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'report_id' => Report::inRandomOrder()->firstOrFail()->id,
            'title' => $this->faker->title(),
            'heading' => $this->faker->sentence(3),
            'content' => $this->faker->sentence('50'),
        ];
    }
}
