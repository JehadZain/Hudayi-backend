<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobBook;
use App\Repositories\Common\V1\BookRepository;

class MobBookRepository extends BookRepository implements IMobBook
{
    public function booksList()
    {
        // return new $resource($this->model::get());
    }
}
