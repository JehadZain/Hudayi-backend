<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\INote;
use App\Models\Note;
use App\Repositories\BaseRepository;

class NoteRepository extends BaseRepository implements INote
{
    protected $model = Note::class;

    public function __construct()
    {
        $this->build();
    }
}
