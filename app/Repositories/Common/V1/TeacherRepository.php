<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\ITeacher;
use App\Models\Users\Teacher;
use App\Models\Users\User;
use App\Repositories\BaseRepository;

class TeacherRepository extends BaseRepository implements ITeacher
{
    protected $model = Teacher::class;

    protected $associate = User::class;

    public function __construct()
    {
        $this->build();
    }
}
