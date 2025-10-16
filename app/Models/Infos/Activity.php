<?php

namespace App\Models\Infos;

use App\Models\ActivityType;
use App\Models\Participant;
use App\Models\Properties\ClassRoom;
use App\Models\Users\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id'];

    public $quickSearchableArray = ['name'];

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function activityType(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function scopeAppActivity($query)
    {
        return $query->select(
            'id',
            'class_room_id',
            'activity_type_id',
            'teacher_id',
            'name',
            'place',
            'cost',
            'result',
            'note',
            'start_datetime',
            'end_datetime',
            'image'
        )->with('activityType')
            ->with('teacher.user:id,first_name,last_name,image')
            ->with('participants.student.user:id,first_name,last_name,image');
    }
}
