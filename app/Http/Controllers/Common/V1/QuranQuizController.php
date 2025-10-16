<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppQuranQuiz;
use App\Interfaces\Common\V1\IQuranQuiz;
//model
use App\Interfaces\Mobile\V1\IMobileQuranQuiz;
use App\Models\QuranQuiz;

class QuranQuizController extends Controller
{
    protected $model = QuranQuiz::class;

    public function __construct(IQuranQuiz $model, IAppQuranQuiz $appModel, IMobileQuranQuiz $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;

    }
}
