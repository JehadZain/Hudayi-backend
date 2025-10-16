<?php

namespace App\Models\Infos;

use App\Models\Properties\ClassRoom;
use App\Models\Properties\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use HasFactory,SoftDeletes;

    public $quickSearchableArray = ['name', 'id'];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function classRooms(): HasMany
    {
        return $this->hasMany(ClassRoom::class);
        //            ->where("is_approved",true);
    }
}
