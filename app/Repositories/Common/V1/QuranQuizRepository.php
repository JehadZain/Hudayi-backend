<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IQuranQuiz;
use App\Models\QuranQuiz;
use App\Repositories\BaseRepository;

class QuranQuizRepository extends BaseRepository implements IQuranQuiz
{
    protected $model = QuranQuiz::class;

    public function __construct()
    {
        $this->build();
    }
}
