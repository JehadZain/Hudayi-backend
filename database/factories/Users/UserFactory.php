<?php

namespace Database\Factories\Users;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use function fake;
use function now;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Users\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $address = $this->faker->randomElement(['عفرين', 'إدلب', 'إعزاز', 'الباب', 'جنديرس']);

        return [
            'email' => fake()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'username' => $this->faker->userName(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'phone' => $this->faker->numerify('###-###-####'),

            'identity_number' => $this->faker->numerify('###-###-##'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'birth_date' => $this->faker->dateTimeBetween('-30 years', '-10 years'),
            'birth_place' => $this->faker->randomElement(['Syria', 'Turkey']),
            'father_name' => $this->faker->name($gender = 'male'),
            'mother_name' => $this->faker->name($gender = 'female'),
            'qr_code' => $this->faker->uuid(),
            'blood_type' => 'BP',
            'note' => $this->faker->sentence(20),
            'current_address' => $address,
            'is_has_disease' => $this->faker->randomElement(['0', '1']),
            'is_has_treatment' => $this->faker->randomElement(['0', '1']),
            'are_there_disease_in_house' => $this->faker->randomElement(['1', '0']),
            'are_there_disease_in_family' => $this->faker->randomElement(['1', '0']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
