<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\MosqueController;
use App\Http\Requests\App\V1\AppMosqueCreateRequest;
use App\Http\Requests\App\V1\AppMosqueEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppMosqueController extends MosqueController
{
    public function appGetAllMosques(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetMosqueById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->mosqueId)));
    }

    public function appCreateMosque(AppMosqueCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateMosque(AppMosqueEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->mosqueId));
    }

    public function appDeleteMosque(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->mosqueId)));
    }
}
