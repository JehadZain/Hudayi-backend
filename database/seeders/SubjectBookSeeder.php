<?php

namespace Database\Seeders;

use App\Models\Infos\SubjectBook;
use Illuminate\Database\Seeder;

class SubjectBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubjectBook::factory()->count(300)->create();
    }
}
