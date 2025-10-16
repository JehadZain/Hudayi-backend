<?php

namespace Database\Factories\Infos;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Infos\Certification>
 */
class CertificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->firstOrFail()->id,
            'qr_code' => $this->faker->numerify('H-###-###'),
            'certificate_type_id' => rand(1, 3),
            'issuing_date' => $this->faker->dateTimeBetween('-20 years', 'now'),
            'expiration_date' => $this->faker->dateTimeBetween('-2 years', '+10 years'),

        ];
    }
}
