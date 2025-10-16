<?php

namespace Database\Factories\Morphs;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Morphs\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'contactable_type' => $this->faker->randomElement(['school', 'mosque']),
            'contactable_id' => $this->faker->numberBetween(1, 100),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'whatsapp' => $this->faker->phoneNumber(),
            'facebook' => $this->faker->numerify('https://www.facebook.com/####-##'),
            'instagram' => $this->faker->numerify('https://www.instagram.com/######/'),
        ];
    }
}
