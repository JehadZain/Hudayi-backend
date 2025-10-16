<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppNote;
use App\Interfaces\Common\V1\INote;
//model
use App\Interfaces\Mobile\V1\IMobileNote;
use App\Models\Note;

class NoteController extends Controller
{
    protected $model = Note::class;

    public function __construct(INote $model, IAppNote $appModel, IMobileNote $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
