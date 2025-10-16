<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\AnalyticsController;
use App\Http\Requests\Mobile\V1\MobileActivityCreateRequest;
use App\Http\Requests\Mobile\V1\MobileActivityEditRequest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileAnalyticsController extends AnalyticsController
{
    public function mobileGetPropertiesAnalytics(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetGeneralCounts(Request $request, string $timeFilter, ?string $customStartDate, ?string $customEndDate): JsonResponse
    {
        //        dd($customStartDate == null );
        $customStartDate = $customStartDate != null ? Carbon::parse($customStartDate) : null;
        $customEndDate = $customEndDate != null ? Carbon::parse($customEndDate) : null;

        return $this->response($request, $this->mobileRepo->mobileGetGeneralCounts($timeFilter, $customStartDate, $customEndDate));
    }

    //
    //    public function mobileCreateActivity(MobileActivityCreateRequest $request): JsonResponse
    //    {
    //        return $this->response($request, $this->mobileRepo->mobileCreateObject($request));
    //    }
    //
    //    public function mobileUpdateActivity(MobileActivityEditRequest $request): JsonResponse
    //    {
    //        return $this->response($request, $this->mobileRepo->mobileUpdateObject($request, $request->activityId));
    //    }
    //
    //    public function mobileDeleteActivity(Request $request): JsonResponse
    //    {
    //        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->activityId)));
    //    }
}
