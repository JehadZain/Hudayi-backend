<?php

namespace Database\Factories\Report;

use App\Models\Morphs\Report;
use App\Models\Users\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report\ReportReviewer>
 */
class ReportReviewerFactory extends Factory
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
            'admin_id' => Admin::inRandomOrder()->firstOrFail()->id,
            'opened_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
