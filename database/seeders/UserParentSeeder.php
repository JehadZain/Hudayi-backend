<?php

namespace Database\Seeders;

use App\Models\Users\UserParent;
use Illuminate\Database\Seeder;

class UserParentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserParent::factory()->count(50)->create();
    }
}
