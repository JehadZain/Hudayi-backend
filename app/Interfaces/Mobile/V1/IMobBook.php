<?php

namespace App\Interfaces\Mobile\V1;

use App\Interfaces\Common\V1\IBook;

interface IMobBook extends IBook
{
    public function booksList();
}
