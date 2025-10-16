<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\Common\V1\IPatientTreatment;
use App\Interfaces\App\V1\IAppPatientTreatment;
use App\Interfaces\Mobile\V1\IMobilePatientTreatment;
use App\Models\Infos\PatientTreatment;

class PatientTreatmentController extends Controller
{
    protected $model = PatientTreatment::class;
    protected $baseRepo;
    protected $appRepo;
    protected $mobileRepo;

    public function __construct(IPatientTreatment $patientTreatment, IAppPatientTreatment $appPatientTreatment, IMobilePatientTreatment $mobilePatientTreatment)
    {
        $this->baseRepo = $patientTreatment;
        $this->appRepo = $appPatientTreatment;
        $this->mobileRepo = $mobilePatientTreatment;
    }
}
