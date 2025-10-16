<?php

namespace App\Traits\Morphs;

use App\Models\Morphs\Attendance;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Attendanceable
{
    public function attendances(): MorphMany
    {
        return $this->morphMany(Attendance::class, 'attendanceable');
    }
}
