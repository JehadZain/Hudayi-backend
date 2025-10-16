<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IStudent;
use App\Models\Users\Student;
use App\Models\Users\User;
use App\Repositories\BaseRepository;

class StudentRepository extends BaseRepository implements IStudent
{
    protected $model = Student::class;

    protected $associate = User::class;

    public function __construct()
    {
        $this->build();
    }
}
