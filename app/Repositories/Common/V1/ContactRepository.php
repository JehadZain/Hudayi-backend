<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IContact;
use App\Models\Morphs\Contact;
use App\Repositories\BaseRepository;

class ContactRepository extends BaseRepository implements IContact
{
    protected $model = Contact::class;

    public function __construct()
    {
        $this->build();
    }
}
