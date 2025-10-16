<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IQuiz;
use App\Models\Quiz;
use App\Repositories\BaseRepository;

class QuizRepository extends BaseRepository implements IQuiz
{
    protected $model = Quiz::class;

    public function __construct()
    {
        $this->build();
    }
}
