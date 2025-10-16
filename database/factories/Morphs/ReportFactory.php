<?php

namespace Database\Factories\Morphs;

use App\Models\Morphs\Report;
use App\Models\Report\ReportType;
use App\Models\Users\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Morphs\Report>
 */
class ReportFactory extends Factory
{
    protected $model = Report::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'reportable_type' => $this->faker->randomElement(['school', 'teacher']),
            'reportable_id' => $this->faker->numberBetween(1, 20),
            'admin_id' => Admin::inRandomOrder()->firstOrFail()->id,
            'report_type_id' => ReportType::inRandomOrder()->firstOrFail()->id,

        ];
    }
}
