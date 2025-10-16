<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\OrganizationAdminController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileOrganizationAdminController extends OrganizationAdminController
{
    public function mobileGetAllOrganizationsAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetOrganizationAdminById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->organizationId)));
    }

    public function mobileCreateOrganizationAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject($request->all()));
    }

    public function mobileUpdateOrganizationAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject($request->all(), $request->organizationId));
    }

    public function mobileDeleteOrganizationAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->organizationId)));
    }
}
