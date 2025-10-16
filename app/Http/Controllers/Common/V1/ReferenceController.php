<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\Common\V1\IReference;
use App\Interfaces\App\V1\IAppReference;
use App\Interfaces\Mobile\V1\IMobileReference;
use App\Models\Infos\Reference;

class ReferenceController extends Controller
{
    protected $model = Reference::class;
    protected $baseRepo;
    protected $appRepo;
    protected $mobileRepo;

    public function __construct(IReference $reference, IAppReference $appReference, IMobileReference $mobileReference)
    {
        $this->baseRepo = $reference;
        $this->appRepo = $appReference;
        $this->mobileRepo = $mobileReference;
    }
}
