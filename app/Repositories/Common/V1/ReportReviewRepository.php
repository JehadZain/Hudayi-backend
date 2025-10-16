<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IReportReview;
use App\Models\Report\ReportReviewer;
use App\Repositories\BaseRepository;

class ReportReviewRepository extends BaseRepository implements IReportReview
{
    protected $model = ReportReviewer::class;

    public function __construct()
    {
        $this->build();
    }
}
