<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\OrganizationAdminController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppOrganizationAdminController extends OrganizationAdminController
{
    public function appGetAllOrganizationsAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetOrganizationAdminById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->organizationId)));
    }

    public function appCreateOrganizationAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject($request->all()));
    }

    public function appUpdateOrganizationAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject($request->all(), $request->organizationId));
    }

    public function appDeleteOrganizationAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->organizationId)));
    }
}
