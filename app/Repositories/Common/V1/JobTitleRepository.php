<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IJobTitle;
use App\Models\Infos\JobTitle;
use App\Repositories\BaseRepository;

class JobTitleRepository extends BaseRepository implements IJobTitle
{
    protected $model = JobTitle::class;

    public function __construct()
    {
        $this->build();
    }
}
