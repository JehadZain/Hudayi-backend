<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\BookController;
use App\Http\Requests\Mobile\V1\MobileBookCreateRequest;
use App\Http\Requests\Mobile\V1\MobileBookEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileBookController extends BookController
{
    //repoFunctionModel
    public function mobileGetAllBooks(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetBookById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->bookId)));
    }

    public function mobileCreateBook(MobileBookCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject($request));
    }

    public function mobileUpdateBook(MobileBookEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject($request, $request->bookId));
    }

    public function mobileDeleteBook(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->bookId)));
    }

    public function mobileGetAllSchoolBooks(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileGetAllSchoolBooks($request));
    }

    public function mobileGetAllMosqueBooks(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileGetAllMosqueBooks($request));
    }
}
