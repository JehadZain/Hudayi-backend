<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\StatusController;
use App\Http\Requests\Mobile\V1\MobileStatusCreateRequest;
use App\Http\Requests\Mobile\V1\MobileStatusEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileStatusController extends StatusController
{
    //repoFunctionModel
    public function mobileGetAllStatuses(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetStatusById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->statusId)));
    }

    public function mobileCreateStatus(MobileStatusCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateStatus(MobileStatusEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->statusId));
    }

    public function mobileDeleteStatus(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->statusId)));
    }
}
