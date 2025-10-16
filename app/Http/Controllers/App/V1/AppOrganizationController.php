<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\OrganizationController;
use App\Http\Requests\App\V1\AppOrganizationCreateRequest;
use App\Http\Requests\App\V1\AppOrganizationEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppOrganizationController extends OrganizationController
{
    public function appGetAllOrganizations(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetOrganizationById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->organizationId)));
    }

    public function appCreateOrganization(AppOrganizationCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateOrganization(AppOrganizationEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->organizationId));
    }

    public function appDeleteOrganization(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->organizationId)));
    }
}
