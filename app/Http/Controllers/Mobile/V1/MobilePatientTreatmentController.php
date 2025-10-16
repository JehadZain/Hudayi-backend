<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\PatientTreatmentController;
use App\Http\Requests\Mobile\V1\MobilePatientTreatmentCreateRequest;
use App\Http\Requests\Mobile\V1\MobilePatientTreatmentEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobilePatientTreatmentController extends PatientTreatmentController
{
    public function mobileGetAllPatientTreatments(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetPatientTreatmentById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->treatmentId)));
    }

    public function mobileCreatePatientTreatment(MobilePatientTreatmentCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdatePatientTreatment(MobilePatientTreatmentEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->treatmentId));
    }

    public function mobileDeletePatientTreatment(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->treatmentId)));
    }
}
