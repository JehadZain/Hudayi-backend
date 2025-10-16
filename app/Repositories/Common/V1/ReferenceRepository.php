<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IReference;
use App\Models\Morphs\Reference;
use App\Repositories\BaseRepository;

class ReferenceRepository extends BaseRepository implements IReference
{
    protected $model = Reference::class;

    public function __construct()
    {
        $this->build();
    }
}
