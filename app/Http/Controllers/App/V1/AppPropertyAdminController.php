<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\PropertyAdminController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppPropertyAdminController extends PropertyAdminController
{
    public function appGetAllPropertyAdmins(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetPropertyAdminById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->propertyId)));
    }

    public function appCreatePropertyAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject($request->all()));
    }

    public function appTransferPropertyAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appTransferAdmin($request->all()));
    }

    public function appUpdatePropertyAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject($request->all(), $request->propertyId));
    }

    public function appDeletePropertyAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->propertyId)));
    }
}
