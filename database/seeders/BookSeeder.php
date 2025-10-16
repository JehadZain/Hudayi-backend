<?php

namespace Database\Seeders;

use App\Models\Infos\Book;
use App\Models\Infos\Subject;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = Subject::pluck('id');

        foreach ($subjects as $subject) {
            Book::factory()->count(4)->create();
        }
    }
}
