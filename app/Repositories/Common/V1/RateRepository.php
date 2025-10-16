<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IRate;
use App\Models\Morphs\Rate;
use App\Repositories\BaseRepository;

class RateRepository extends BaseRepository implements IRate
{
    protected $model = Rate::class;

    public function __construct()
    {
        $this->build();
    }
}
