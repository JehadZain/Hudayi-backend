<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\Common\V1\IAddress;
use App\Interfaces\App\V1\IAppAddress;
use App\Interfaces\Mobile\V1\IMobileAddress;
use App\Models\Infos\Address;

class AddressController extends Controller
{
    protected $model = Address::class;
    protected $baseRepo;
    protected $appRepo;
    protected $mobileRepo;

    public function __construct(IAddress $address, IAppAddress $appAddress, IMobileAddress $mobileAddress)
    {
        $this->baseRepo = $address;
        $this->appRepo = $appAddress;
        $this->mobileRepo = $mobileAddress;
    }
}
