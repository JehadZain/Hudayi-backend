<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppActivityParticipants;
use App\Interfaces\Common\V1\IActivityParticipants;
use App\Interfaces\Mobile\V1\IMobileActivityParticipants;
//model
use App\Models\Participant;

class ActivityParticipantsController extends Controller
{
    protected $model = Participant::class;

    public function __construct(IActivityParticipants $model, IAppActivityParticipants $appModel, IMobileActivityParticipants $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
