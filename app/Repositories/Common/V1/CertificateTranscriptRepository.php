<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\ICertificateTranscript;
use App\Models\Infos\CertificateTranscript;
use App\Repositories\BaseRepository;

class CertificateTranscriptRepository extends BaseRepository implements ICertificateTranscript
{
    protected $model = CertificateTranscript::class;

    public function __construct()
    {
        $this->build();
    }
}
