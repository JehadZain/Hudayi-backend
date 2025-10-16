<?php

namespace App\Models\Properties;

use App\Models\Users\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRoomStudent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['class_room_id', 'student_id', 'joined_at', 'left_at', 'created_at'/* other fillable columns */];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }
}
