<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\PatientController;
use App\Http\Requests\Mobile\V1\MobilePatientCreateRequest;
use App\Http\Requests\Mobile\V1\MobilePatientEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobilePatientController extends PatientController
{
    public function mobileGetAllPatients(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetPatientById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->patientId)));
    }

    public function mobileCreatePatient(MobilePatientCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdatePatient(MobilePatientEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->patientId));
    }

    public function mobileDeletePatient(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->patientId)));
    }
}
