<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\PatientTreatmentController;
use App\Http\Requests\App\V1\AppPatientTreatmentCreateRequest;
use App\Http\Requests\App\V1\AppPatientTreatmentEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppPatientTreatmentController extends PatientTreatmentController
{
    public function appGetAllPatientTreatments(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetPatientTreatmentById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->treatmentId)));
    }

    public function appCreatePatientTreatment(AppPatientTreatmentCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdatePatientTreatment(AppPatientTreatmentEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->treatmentId));
    }

    public function appDeletePatientTreatment(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->treatmentId)));
    }
}
