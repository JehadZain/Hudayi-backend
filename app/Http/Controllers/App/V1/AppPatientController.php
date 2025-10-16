<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\PatientController;
use App\Http\Requests\App\V1\AppPatientCreateRequest;
use App\Http\Requests\App\V1\AppPatientEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppPatientController extends PatientController
{
    public function appGetAllPatients(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetPatientById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->patientId)));
    }

    public function appCreatePatient(AppPatientCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdatePatient(AppPatientEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->patientId));
    }

    public function appDeletePatient(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->patientId)));
    }
}
