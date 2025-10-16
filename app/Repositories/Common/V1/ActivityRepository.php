<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IActivity;
use App\Models\Infos\Activity;
use App\Repositories\BaseRepository;

class ActivityRepository extends BaseRepository implements IActivity
{
    protected $model = Activity::class;

    public function __construct()
    {
        $this->build();
    }
}
