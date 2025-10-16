<?php

namespace Database\Seeders;

use App\Models\Properties\Property;
use App\Models\properties\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
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
            $property->name = ('مدرسة '.str()->random(20));
            $property->capacity = rand(5, 30);
            $property->branch_id = rand(1, 5);
            $property->description = 'المَسْجِد أو الجامع هو دار عبادة المسلمين، وتُقام فيه الصلوات الخمس المفروضة وغيرها، وسمي مسجداً لأنه مكان للسجود لله، ويُطلق على المسجد أيضاً اسم جامع، وخاصةً إذا كان كبيراً، وفي الغالب يُطلق على اسم «جامع» لمن يجمع الناس لأداء صلاة الجمعة فيه فكل جامع مسجد وليس كل مسجد بجامع، كذلك يطلق اسم مصلى بدل من اسم مسجد عند أداء بعض الصلوات الخمس المفروضة فيه وليس كلها مثل مصليات المدارس والمؤسسات والشركات وطرق السفر وغيرها التي غالباً ما يؤدى فيها صلاة محدودة بحسب الفترة الزمنية الحالية، ويدعى للصلاة في المسجد عن طريق الأذان، وذلك خمس مرات في اليوم.';
            $mosque = new School();
            $mosque->save();
            $mosque->property()->save($property);
        }
    }
}
