<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\Common\V1\IPatient;
use App\Interfaces\App\V1\IAppPatient;
use App\Interfaces\Mobile\V1\IMobilePatient;
use App\Models\Users\Patient;

class PatientController extends Controller
{
    protected $model = Patient::class;
    protected $baseRepo;
    protected $appRepo;
    protected $mobileRepo;

    public function __construct(IPatient $patient, IAppPatient $appPatient, IMobilePatient $mobilePatient)
    {
        $this->baseRepo = $patient;
        $this->appRepo = $appPatient;
        $this->mobileRepo = $mobilePatient;
    }
}
