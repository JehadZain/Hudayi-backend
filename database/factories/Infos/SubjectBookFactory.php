<?php

namespace Database\Factories\Infos;

use App\Models\Infos\Book;
use App\Models\Infos\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubjectBook>
 */
class SubjectBookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'subject_id' => Subject::inRandomOrder()->firstOrFail()->id,
            'book_id' => Book::inRandomOrder()->firstOrFail()->id,
        ];
    }
}
