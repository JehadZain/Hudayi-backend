<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IBook;
use App\Models\Infos\Book;
use App\Repositories\BaseRepository;

class BookRepository extends BaseRepository implements IBook
{
    protected $model = Book::class;

    public function __construct()
    {
        $this->build();
    }
}
