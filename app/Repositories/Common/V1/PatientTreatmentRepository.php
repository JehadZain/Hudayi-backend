<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IPatientTreatment;
use App\Models\Infos\PatientTreatment;
use App\Repositories\BaseRepository;

class PatientTreatmentRepository extends BaseRepository implements IPatientTreatment
{
    protected $model = PatientTreatment::class;

    public function __construct()
    {
        $this->build();
    }
}
