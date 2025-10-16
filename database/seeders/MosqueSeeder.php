<?php

namespace Database\Seeders;

use App\Models\properties\Mosque;
use App\Models\Properties\Property;
use Illuminate\Database\Seeder;

class MosqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i <= 100; $i++) {
            $property = new Property();
            $property->name = 'مسجد '.str()->random(20);
            $property->capacity = rand(5, 30);
            $property->branch_id = rand(1, 5);
            $property->description = 'إن المدرسة لها أهمية كبيرة جدًا في حياتنا، فهي المكان الذي يضيء العقول، ويفتح أبواب الأمل والسعادة، وهي المصباح الذي ينير الطريق أمام العلم والمعرفة، ويمحو ظلام الجهل.';

            $mosque = new Mosque();
            $mosque->save();
            $mosque->property()->save($property);
        }
    }
}
