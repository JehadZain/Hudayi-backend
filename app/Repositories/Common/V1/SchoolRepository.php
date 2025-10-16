<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\ISchool;
use App\Models\Properties\Property;
use App\Models\properties\School;
use App\Repositories\BaseRepository;

class SchoolRepository extends BaseRepository implements ISchool
{
    protected $model = School::class;

    protected $morphTo = Property::class;

    public function __construct()
    {
        $this->build();
    }
}
