<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\ContactController;
use App\Http\Requests\Mobile\V1\MobileContactCreateRequest;
use App\Http\Requests\Mobile\V1\MobileContactEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileContactController extends ContactController
{
    public function mobileGetAllContacts(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetContactById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->contactId)));
    }

    public function mobileCreateContact(MobileContactCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateContact(MobileContactEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->contactId));
    }

    public function mobileDeleteContact(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->contactId)));
    }
}
