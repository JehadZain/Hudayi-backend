<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppBook; //model
use App\Interfaces\Common\V1\IBook;
use App\Interfaces\Mobile\V1\IMobileBook;
use App\Models\Infos\Book;

class BookController extends Controller
{
    protected $model = Book::class;

    public function __construct(IBook $model, IAppBook $appModel, IMobileBook $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
