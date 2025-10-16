<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\AnalyticsController;
use App\Http\Requests\App\V1\AppActivityCreateRequest;
use App\Http\Requests\App\V1\AppActivityEditRequest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Validators\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AppAnalyticsController extends AnalyticsController
{
    public function appGetPropertiesAnalytics(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetGeneralCounts(Request $request, string $timeFilter, ?string $customStartDate, ?string $customEndDate): JsonResponse
    {
//                dd($customStartDate == null );
        $customStartDate = $customStartDate != null ? Carbon::parse($customStartDate) : null;
        $customEndDate = $customEndDate != null ? Carbon::parse($customEndDate) : null;

        return $this->response($request, $this->appRepo->appGetGeneralCounts($timeFilter, $customStartDate, $customEndDate));
    }

    public function appGetTopLearners(Request $request, string $timeFilter, ?string $customStartDate, ?string $customEndDate): JsonResponse
    {

        $customStartDate = $customStartDate != null ? Carbon::parse($customStartDate) : null;
        $customEndDate = $customEndDate != null ? Carbon::parse($customEndDate) : null;

        return $this->response($request, $this->appRepo->appGetTopLearners($timeFilter, $customStartDate, $customEndDate));
    }


    public function appExportData(Request $request, string $start_date, string $end_date): StreamedResponse
    {

        $response = $this->appRepo->appExportData( $start_date,  $end_date);

        $callback = $response->data->callback;
        $headers = $response->data->headers;

        // Create a new StreamedResponse with the updated headers
        $streamedResponse = new StreamedResponse($callback, 200, $headers);

        // Explicitly set UTF-8 headers
        $streamedResponse->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $streamedResponse->headers->set('Content-Transfer-Encoding', 'utf-8');
        $streamedResponse->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $streamedResponse->headers->set('Pragma', 'no-cache');
        $streamedResponse->headers->set('Expires', '0');

        return $streamedResponse;

    }

//    public function apptestData(Request $request, string $propertyId, string $start_date, string $end_date): StreamedResponse
//    {
//        dd('test');
//        $test = "test";
//    return $test;
//    }

    public function appPropertyExportData(Request $request, string $propertyId, string $start_date = null, string $end_date = null): StreamedResponse{
//    public function appPropertyExportData(Request $request, string $propertyId, string $start_date = null, string $end_date = null): StreamedResponse    {
//        dd('co');

        $response = $this->appRepo->appPropertyExportData($propertyId, $start_date, $end_date);

//        if ($response->status !== 'success') {
//            return $this->setResponse($response->status, $response->data, $response->errors);
//        }

        $callback = $response->data->callback;
        $headers = $response->data->headers;

        // Create a new StreamedResponse with the updated headers
        $streamedResponse = new StreamedResponse($callback, 200, $headers);

        // Explicitly set UTF-8 headers
        $streamedResponse->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $streamedResponse->headers->set('Content-Transfer-Encoding', 'utf-8');
        $streamedResponse->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $streamedResponse->headers->set('Pragma', 'no-cache');
        $streamedResponse->headers->set('Expires', '0');

        return $streamedResponse;

    }

    //
    //    public function appCreateActivity(AppActivityCreateRequest $request): JsonResponse
    //    {
    //        return $this->response($request, $this->appRepo->appCreateObject($request));
    //    }
    //
    //    public function appUpdateActivity(AppActivityEditRequest $request): JsonResponse
    //    {
    //        return $this->response($request, $this->appRepo->appUpdateObject($request, $request->activityId));
    //    }
    //
    //    public function appDeleteActivity(Request $request): JsonResponse
    //    {
    //        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->activityId)));
    //    }
}
