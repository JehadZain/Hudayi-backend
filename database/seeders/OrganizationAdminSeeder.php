<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organization_admins')->insert([
            'admin_id' => 1,
            'organization_id' => 1,
        ]);

        DB::table('organization_admins')->insert([
            'admin_id' => 2,
            'organization_id' => 1,
        ]);
    }
}
