<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\Common\V1\IScore;
use App\Interfaces\App\V1\IAppScore;
use App\Interfaces\Mobile\V1\IMobileScore;
use App\Models\Infos\Score;

class ScoreController extends Controller
{
    protected $model = Score::class;
    protected $baseRepo;
    protected $appRepo;
    protected $mobileRepo;

    public function __construct(IScore $score, IAppScore $appScore, IMobileScore $mobileScore)
    {
        $this->baseRepo = $score;
        $this->appRepo = $appScore;
        $this->mobileRepo = $mobileScore;
    }
}
