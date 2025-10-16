<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\ICalendar;
use App\Models\Calendar;
use App\Repositories\BaseRepository;

class CalendarRepository extends BaseRepository implements ICalendar
{
    protected $model = Calendar::class;

    public function __construct()
    {
        $this->build();
    }
}
