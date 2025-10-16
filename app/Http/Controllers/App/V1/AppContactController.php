<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\ContactController;
use App\Http\Requests\App\V1\AppContactCreateRequest;
use App\Http\Requests\App\V1\AppContactEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppContactController extends ContactController
{
    public function appGetAllContacts(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetContactById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->contactId)));
    }

    public function appCreateContact(AppContactCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateContact(AppContactEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->contactId));
    }

    public function appDeleteContact(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->contactId)));
    }
}
