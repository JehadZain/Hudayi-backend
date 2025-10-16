<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\ICertificateDetail;
use App\Models\Infos\CertificateDetail;
use App\Repositories\BaseRepository;

class CertificateDetailRepository extends BaseRepository implements ICertificateDetail
{
    protected $model = CertificateDetail::class;

    public function __construct()
    {
        $this->build();
    }
}
