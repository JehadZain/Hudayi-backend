<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\AddressController;
use App\Http\Requests\App\V1\AppAddressCreateRequest;
use App\Http\Requests\App\V1\AppAddressEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppAddressController extends AddressController
{
    public function appGetAllAddresses(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetAddressById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->addressId)));
    }

    public function appCreateAddress(AppAddressCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateAddress(AppAddressEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->addressId));
    }

    public function appDeleteAddress(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->addressId)));
    }
}
