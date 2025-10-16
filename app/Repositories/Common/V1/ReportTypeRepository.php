<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IReportType;
use App\Models\Report\ReportType;
use App\Repositories\BaseRepository;

class ReportTypeRepository extends BaseRepository implements IReportType
{
    protected $model = ReportType::class;

    public function __construct()
    {
        $this->build();
    }
}
