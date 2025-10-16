<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IPatient;
use App\Models\Infos\Patient;
use App\Repositories\BaseRepository;

class PatientRepository extends BaseRepository implements IPatient
{
    protected $model = Patient::class;

    public function __construct()
    {
        $this->build();
    }
}
