<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IReportContent;
use App\Models\Report\ReportContent;
use App\Repositories\BaseRepository;

class ReportContentRepository extends BaseRepository implements IReportContent
{
    protected $model = ReportContent::class;

    public function __construct()
    {
        $this->build();
    }
}
