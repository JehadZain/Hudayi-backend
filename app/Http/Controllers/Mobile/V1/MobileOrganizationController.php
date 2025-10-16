<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\OrganizationController;
use App\Http\Requests\Mobile\V1\MobileOrganizationCreateRequest;
use App\Http\Requests\Mobile\V1\MobileOrganizationEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileOrganizationController extends OrganizationController
{
    public function mobileGetAllOrganizations(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetOrganizationById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->organizationId)));
    }

    public function mobileCreateOrganization(MobileOrganizationCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateOrganization(MobileOrganizationEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->organizationId));
    }

    public function mobileDeleteOrganization(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->organizationId)));
    }
}
