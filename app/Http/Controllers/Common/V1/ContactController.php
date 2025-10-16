<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\Common\V1\IContact;
use App\Interfaces\App\V1\IAppContact;
use App\Interfaces\Mobile\V1\IMobileContact;
use App\Models\Infos\Contact;

class ContactController extends Controller
{
    protected $model = Contact::class;
    protected $baseRepo;
    protected $appRepo;
    protected $mobileRepo;

    public function __construct(IContact $contact, IAppContact $appContact, IMobileContact $mobileContact)
    {
        $this->baseRepo = $contact;
        $this->appRepo = $appContact;
        $this->mobileRepo = $mobileContact;
    }
}
