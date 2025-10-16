<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IStatus;
use App\Models\Infos\Status;
use App\Repositories\BaseRepository;

class StatusRepository extends BaseRepository implements IStatus
{
    protected $model = Status::class;

    public function __construct()
    {
        $this->build();
    }
}
