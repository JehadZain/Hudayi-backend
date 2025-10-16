<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IMosque;
use App\Models\properties\Mosque;
use App\Models\Properties\Property;
use App\Repositories\BaseRepository;

class MosqueRepository extends BaseRepository implements IMosque
{
    protected $model = Mosque::class;

    protected $morphTo = Property::class;

    public function __construct()
    {
        $this->build();
    }
}
