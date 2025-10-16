<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\SubjectController;
use App\Http\Requests\Mobile\V1\MobileSubjectCreateRequest;
use App\Http\Requests\Mobile\V1\MobileSubjectEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileSubjectController extends SubjectController
{
    //repoFunctionModel
    public function mobileGetAllSubjects(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetSubjectById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->subjectId)));
    }

    public function mobileCreateSubject(MobileSubjectCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateSubject(MobileSubjectEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->subjectId));
    }

    public function mobileDeleteSubject(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->subjectId)));
    }
}
