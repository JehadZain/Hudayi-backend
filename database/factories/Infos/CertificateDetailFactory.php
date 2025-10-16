<?php

namespace Database\Factories\Infos;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Infos\CertificateDetail>
 */
class CertificateDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'certificate of '.str()->random(),
            'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Porro, doloremque.',
        ];
    }
}
