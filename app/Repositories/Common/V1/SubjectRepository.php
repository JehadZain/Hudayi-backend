<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\ISubject;
use App\Models\Infos\Subject;
use App\Repositories\BaseRepository;

class SubjectRepository extends BaseRepository implements ISubject
{
    protected $model = Subject::class;

    public function __construct()
    {
        $this->build();
    }
}
