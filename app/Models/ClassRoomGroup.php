<?php

namespace App\Models;

use App\Models\Infos\Grade;
use App\Models\Infos\Session;
use App\Models\Properties\ClassRoom;
use App\Models\Properties\ClassRoomStudent;
use App\Models\Properties\ClassRoomTeacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRoomGroup extends Model
{
    use HasFactory, SoftDeletes;

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function classRoomTeachers(): HasMany
    {
        return $this->hasMany(ClassRoomTeacher::class);
    }

    public function classRoomGroupStudents(): HasMany
    {
        return $this->hasMany(ClassRoomStudent::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }
}
