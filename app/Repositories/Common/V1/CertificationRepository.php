<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\ICertification;
use App\Models\Infos\Certification;
use App\Repositories\BaseRepository;

class CertificationRepository extends BaseRepository implements ICertification
{
    protected $model = Certification::class;

    public function __construct()
    {
        $this->build();
    }
}
