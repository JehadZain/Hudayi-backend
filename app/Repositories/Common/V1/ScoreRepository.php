<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IScore;
use App\Models\Morphs\Score;
use App\Repositories\BaseRepository;

class ScoreRepository extends BaseRepository implements IScore
{
    protected $model = Score::class;

    public function __construct()
    {
        $this->build();
    }
}
