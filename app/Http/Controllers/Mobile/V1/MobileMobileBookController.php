<?php

namespace App\Http\Controllers\Mobile\V1;

use Illuminate\Http\JsonResponse;

class MobileAppBookController extends AppBookController
{
    public function getBooksForMobile(): JsonResponse
    {
        $this->setData($this->mobileRepo->booksList());

        return $this->response();
    }
}
