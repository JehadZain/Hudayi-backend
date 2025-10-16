<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\BookController;
use App\Http\Requests\App\V1\AppBookCreateRequest;
use App\Http\Requests\App\V1\AppBookEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppBookController extends BookController
{
    //repoFunctionModel
    public function appGetAllBooks(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetBookById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->bookId)));
    }

    public function appCreateBook(AppBookCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject($request));
    }

    public function appUpdateBook(AppBookEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject($request, $request->bookId));
    }

    public function appDeleteBook(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->bookId)));
    }

    public function appGetAllSchoolBooks(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetAllSchoolBooks($request));
    }

    public function appGetAllMosqueBooks(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetAllMosqueBooks($request));
    }
}
