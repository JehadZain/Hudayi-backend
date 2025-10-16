<?php

namespace App\Interfaces\App\V1;

use App\Interfaces\Common\V1\IAnalytics;
use Carbon\Carbon;

interface IAppAnalytics extends IAnalytics
{
    public function appAll(): object;

    public function appGetGeneralCounts(string $timeFilter = 'all', Carbon $customStartDate = null, Carbon $customEndDate = null): object;
    public function appGetTopLearners(string $timeFilter = 'all', Carbon $customStartDate = null, Carbon $customEndDate = null): object;
    public function appExportData(?string $start_date = null, ?string $end_date = null): object;
    public function appPropertyExportData(string $propertyId, ?string $start_date = null, ?string $end_date = null): object;
    //    public function appCreateObject($object): object;
    //
    //    public function appUpdateObject($object, $id): object;
    //
    //    public function appDeleteObject(string $id): object;
}
