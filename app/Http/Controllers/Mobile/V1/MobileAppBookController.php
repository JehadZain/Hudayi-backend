<?php

namespace App\Http\Controllers\Mobile\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\App\V1\AppBookController;

class MobileAppBookController extends AppBookController
{
    public function getBooksForMobile(): JsonResponse
    {
        $this->setData($this->mobileRepo->booksList());

        return $this->response();
    }
}
