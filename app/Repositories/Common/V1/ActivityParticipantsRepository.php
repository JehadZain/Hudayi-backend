<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IActivityParticipants;
use App\Models\Participant;
use App\Repositories\BaseRepository;

class ActivityParticipantsRepository extends BaseRepository implements IActivityParticipants
{
    protected $model = Participant::class;

    public function __construct()
    {
        $this->build();
    }
}
