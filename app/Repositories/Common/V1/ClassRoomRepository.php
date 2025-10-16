<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IClassRoom;
use App\Models\Properties\ClassRoom;
use App\Repositories\BaseRepository;

class ClassRoomRepository extends BaseRepository implements IClassRoom
{
    protected $model = ClassRoom::class;

    public function __construct()
    {
        $this->build();
    }
}
