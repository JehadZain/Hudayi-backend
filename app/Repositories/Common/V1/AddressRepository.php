<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IAddress;
use App\Models\Morphs\Address;
use App\Repositories\BaseRepository;

class AddressRepository extends BaseRepository implements IAddress
{
    protected $model = Address::class;

    public function __construct()
    {
        $this->build();
    }
}
