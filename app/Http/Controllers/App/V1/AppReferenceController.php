<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\ReferenceController;
use App\Http\Requests\App\V1\AppReferenceCreateRequest;
use App\Http\Requests\App\V1\AppReferenceEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppReferenceController extends ReferenceController
{
    public function appGetAllReferences(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetReferenceById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->referenceId)));
    }

    public function appCreateReference(AppReferenceCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateReference(AppReferenceEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->referenceId));
    }

    public function appDeleteReference(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->referenceId)));
    }
}
