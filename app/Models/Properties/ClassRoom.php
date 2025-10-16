<?php

namespace App\Models\Properties;

use App\Models\Calendar;
use App\Models\Infos\Activity;
use App\Models\Infos\Book;
use App\Models\Infos\Grade;
use App\Models\Infos\Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRoom extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $fillable = ['created_at'/* other fillable columns */];

    public $quickSearchableArray = ['name', 'id'];

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function classRoomTeachers(): HasMany
    {
        return $this->hasMany(ClassRoomTeacher::class);
    }

    public function classRoomStudents(): HasMany
    {
        return $this->hasMany(ClassRoomStudent::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function calendars(): HasMany
    {
        return $this->hasMany(Calendar::class);
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }
}
