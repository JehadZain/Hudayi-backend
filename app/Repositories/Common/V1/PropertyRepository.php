<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IProperty;
use App\Models\Properties\Property;
use App\Repositories\BaseRepository;

class PropertyRepository extends BaseRepository implements IProperty
{
    protected $model = Property::class;

    public function __construct()
    {
        $this->build();
    }
}
