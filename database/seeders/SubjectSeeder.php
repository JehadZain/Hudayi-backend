<?php

namespace Database\Seeders;

use App\Models\Properties\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $subjects = ['حديث', 'تفسير', 'سيرة', 'فقه', 'تجويد', 'ثقافي'];
        $properties = Property::pluck('id');

        foreach ($properties as $property) {
            foreach ($subjects as $subject) {
                DB::table('subjects')->insert([
                    'name' => $subject,
                    'description' => 'هذا العنوان يحتوي على العديد من الكتب الأساسية والثقافية',
                    'property_id' => $property,
                    'created_at' => now(),
                ]);
            }
        }
    }
}
