<?php

namespace Database\Seeders;

use App\Models\Morphs\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Contact::factory()->count(400)->create();
    }
}
