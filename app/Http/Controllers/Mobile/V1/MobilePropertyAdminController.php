<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\PropertyAdminController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobilePropertyAdminController extends PropertyAdminController
{
    public function mobileGetAllPropertyAdmins(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetPropertyAdminById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->propertyId)));
    }

    public function mobileCreatePropertyAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject($request->all()));
    }

    public function mobileTransferPropertyAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileTransferAdmin($request->all()));
    }

    public function mobileUpdatePropertyAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject($request->all(), $request->propertyId));
    }

    public function mobileDeletePropertyAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->propertyId)));
    }
}
