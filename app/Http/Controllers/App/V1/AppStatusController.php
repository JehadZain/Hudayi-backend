<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\StatusController;
use App\Http\Requests\App\V1\AppStatusCreateRequest;
use App\Http\Requests\App\V1\AppStatusEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppStatusController extends StatusController
{
    //repoFunctionModel
    public function appGetAllStatuses(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetStatusById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->statusId)));
    }

    public function appCreateStatus(AppStatusCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateStatus(AppStatusEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->statusId));
    }

    public function appDeleteStatus(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->statusId)));
    }
}
