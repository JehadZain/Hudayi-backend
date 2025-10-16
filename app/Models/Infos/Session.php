<?php

namespace App\Models\Infos;

use App\Models\Properties\ClassRoom;
use App\Models\SessionAttendance;
use App\Models\Users\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id'];

    public $quickSearchableArray = ['name', 'id', 'subject_name', 'place', 'type'];

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function sessionAttendances(): HasMany
    {
        return $this->hasMany(SessionAttendance::class);
    }

    //    public function subject(): BelongsTo
    //    {
    //        return $this->belongsTo(Subject::class);
    //    }
}
