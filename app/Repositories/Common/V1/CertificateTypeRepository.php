<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\ICertificateType;
use App\Models\Infos\CertificateType;
use App\Repositories\BaseRepository;

class CertificateTypeRepository extends BaseRepository implements ICertificateType
{
    protected $model = CertificateType::class;

    public function __construct()
    {
        $this->build();
    }
}
