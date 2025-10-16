<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IGrade;
use App\Models\Infos\Grade;
use App\Repositories\BaseRepository;

class GradeRepository extends BaseRepository implements IGrade
{
    protected $model = Grade::class;

    public function __construct()
    {
        $this->build();
    }
}
