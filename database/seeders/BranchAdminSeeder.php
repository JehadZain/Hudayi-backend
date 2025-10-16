<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('branch_admins')->insert([
            'admin_id' => 3,
            'branch_id' => 1,
        ]);

        DB::table('branch_admins')->insert([
            'admin_id' => 3,
            'branch_id' => 2,
        ]);

    }
}
