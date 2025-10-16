<?php

namespace Database\Factories\Report;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report\ReportType>
 */
class ReportTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type = $this->faker->randomElement(['personal_report', 'teacher_report', 'branch_report']);

        return [
            'name' => $type,
            'description' => $this->faker->sentence(5),
        ];
    }
}
