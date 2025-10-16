<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\QuizController;
use App\Http\Requests\Mobile\V1\MobileQuizCreateRequest;
use App\Http\Requests\Mobile\V1\MobileQuizEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileQuizController extends QuizController
{
    //repoFunctionModel
    public function mobileGetAllQuizzes(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetQuizById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->quizId)));
    }

    public function mobileCreateQuiz(MobileQuizCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateQuiz(MobileQuizEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->quizId));
    }

    public function mobileDeleteQuiz(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->quizId)));
    }
}
