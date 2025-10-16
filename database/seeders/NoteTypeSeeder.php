<?php

namespace Database\Seeders;

use App\Models\NoteType;
use Illuminate\Database\Seeder;

class NoteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NoteType::factory()->count(20)->create();
    }
}
