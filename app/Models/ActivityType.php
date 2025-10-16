<?php

namespace App\Models;

use App\Models\Infos\Activity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityType extends Model
{
    use HasFactory, SoftDeletes;

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
