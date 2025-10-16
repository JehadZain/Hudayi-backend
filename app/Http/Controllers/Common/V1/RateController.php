<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppQuiz;
use App\Interfaces\App\V1\IAppRate;
use App\Interfaces\Common\V1\IQuiz;
use App\Interfaces\Common\V1\IRate;
use App\Interfaces\Mobile\V1\IMobileQuiz;
use App\Interfaces\Mobile\V1\IMobileRate;
use App\Models\Morphs\Rate;
use App\Models\Quiz;

class RateController extends Controller
{
    protected $model = Rate::class;

    public function __construct(IRate $model, IAppRate $appModel, IMobileRate $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
