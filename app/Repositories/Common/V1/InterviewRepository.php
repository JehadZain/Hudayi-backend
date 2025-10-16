<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IInterview;
use App\Models\Interview;
use App\Repositories\BaseRepository;

class InterviewRepository extends BaseRepository implements IInterview
{
    protected $model = Interview::class;

    public function __construct()
    {
        $this->build();
    }
}
