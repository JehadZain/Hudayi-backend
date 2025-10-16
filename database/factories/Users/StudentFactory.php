<?php

namespace Database\Factories\Users;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Users\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $status = $this->faker->randomElement(['نشط', 'معلق']);

        return [
            'user_id' => User::inRandomOrder()->firstOrFail()->id,
            'parent_work' => $this->faker->jobTitle(),
            'status' => $status,
            'parent_phone' => $this->faker->numerify('###-###-####'),
            'family_members_count' => $this->faker->numberBetween(0, 4),
            'who_is_parent' => 'والده محمد الخطيب',
        ];
    }
}
