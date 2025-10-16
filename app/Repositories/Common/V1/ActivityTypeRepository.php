<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IActivityType;
use App\Models\ActivityType;
use App\Repositories\BaseRepository;

class ActivityTypeRepository extends BaseRepository implements IActivityType
{
    protected $model = ActivityType::class;

    public function __construct()
    {
        $this->build();
    }
}
