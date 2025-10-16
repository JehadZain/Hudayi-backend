<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IRateType;
use App\Models\Infos\RateType;
use App\Repositories\BaseRepository;

class RateTypeRepository extends BaseRepository implements IRateType
{
    protected $model = RateType::class;

    public function __construct()
    {
        $this->build();
    }
}
