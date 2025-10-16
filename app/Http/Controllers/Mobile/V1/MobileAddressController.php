<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\AddressController;
use App\Http\Requests\Mobile\V1\MobileAddressCreateRequest;
use App\Http\Requests\Mobile\V1\MobileAddressEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileAddressController extends AddressController
{
    public function mobileGetAllAddresses(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetAddressById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->addressId)));
    }

    public function mobileCreateAddress(MobileAddressCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateAddress(MobileAddressEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->addressId));
    }

    public function mobileDeleteAddress(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->addressId)));
    }
}
