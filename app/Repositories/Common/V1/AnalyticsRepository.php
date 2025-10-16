<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IAnalytics;
use App\Models\Analytics;
use App\Repositories\BaseRepository;

class AnalyticsRepository extends BaseRepository implements IAnalytics
{
    protected $model = Analytics::class;

    public function __construct()
    {
        $this->build();
    }
}
