<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IClassRoomStudent;
use App\Models\Properties\ClassRoomStudent;
use App\Repositories\BaseRepository;

class ClassRoomStudentRepository extends BaseRepository implements IClassRoomStudent
{
    protected $model = ClassRoomStudent::class;

    public function __construct()
    {
        $this->build();
    }
}
