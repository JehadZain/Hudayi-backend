<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppQuiz;
use App\Interfaces\Common\V1\IQuiz;
use App\Interfaces\Mobile\V1\IMobileQuiz;
use App\Models\Quiz;

class QuizController extends Controller
{
    protected $model = Quiz::class;

    public function __construct(IQuiz $model, IAppQuiz $appModel, IMobileQuiz $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
