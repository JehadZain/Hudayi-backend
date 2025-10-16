<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\ISession;
use App\Models\Infos\Session;
use App\Repositories\BaseRepository;

class SessionRepository extends BaseRepository implements ISession
{
    protected $model = Session::class;

    public function __construct()
    {
        $this->build();
    }
}
