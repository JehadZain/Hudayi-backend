<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\QuranQuizController;
use App\Http\Requests\Mobile\V1\MobileQuranQuizCreateRequest;
use App\Http\Requests\Mobile\V1\MobileQuranQuizEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileQuranQuizController extends QuranQuizController
{
    //repoFunctionModel
    public function mobileGetAllQuranQuizzes(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetQuranQuizById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->quranQuizId)));
    }

    public function mobileCreateQuranQuiz(MobileQuranQuizCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateQuranQuiz(MobileQuranQuizEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->quranQuizId));
    }

    public function mobileDeleteQuranQuiz(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->quranQuizId)));
    }
}
