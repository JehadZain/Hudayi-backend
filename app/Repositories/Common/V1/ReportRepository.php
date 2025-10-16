<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IReport;
use App\Models\Morphs\Report;
use App\Repositories\BaseRepository;

class ReportRepository extends BaseRepository implements IReport
{
    protected $model = Report::class;

    public function __construct()
    {
        $this->build();
    }
}
