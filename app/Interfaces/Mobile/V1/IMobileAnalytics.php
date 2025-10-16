<?php

namespace App\Interfaces\Mobile\V1;

use App\Interfaces\Common\V1\IAnalytics;
use Carbon\Carbon;

interface IMobileAnalytics extends IAnalytics
{
    public function mobileAll(): object;

    public function mobileGetGeneralCounts(string $timeFilter = 'all', Carbon $customStartDate = null, Carbon $customEndDate = null): object;

    //    public function mobileCreateObject($object): object;
    //
    //    public function mobileUpdateObject($object, $id): object;
    //
    //    public function mobileDeleteObject(string $id): object;
}
