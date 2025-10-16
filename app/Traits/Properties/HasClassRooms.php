<?php

namespace App\Traits\Properties;

use App\Models\Properties\ClassRoom;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasClassRooms
{
    public function classRooms(): BelongsToMany
    {
        return $this->belongsToMany(ClassRoom::class);
    }
}
