<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IClassRoomTeacher;
use App\Models\Properties\ClassRoomTeacher;
use App\Repositories\BaseRepository;

class ClassRoomTeacherRepository extends BaseRepository implements IClassRoomTeacher
{
    protected $model = ClassRoomTeacher::class;

    public function __construct()
    {
        $this->build();
    }
}
