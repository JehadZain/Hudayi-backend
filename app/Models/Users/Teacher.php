<?php

namespace App\Models\Users;

use App\Models\Infos\Activity;
use App\Models\Infos\Session;
use App\Models\Interview;
use App\Models\Morphs\Rate;
use App\Models\Note;
use App\Models\Properties\ClassRoom;
use App\Models\Quiz;
use App\Models\QuranQuiz;
use App\Traits\Morphs\Addressable;
use App\Traits\Morphs\Contactable;
use App\Traits\Morphs\Referenceable;
use App\Traits\Morphs\Scoreable;
use App\Traits\Morphs\Statusable;
use App\Traits\Users\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory,
        BelongsToUser,
        Referenceable,
        Scoreable,
        SoftDeletes,
        Statusable,
        Addressable,
        Contactable;

    protected $fillable = [
        'wives_count',
        'children_count',
        'marital_status',
    ];

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    public function classRooms(): BelongsToMany
    {
        return $this->belongsToMany(ClassRoom::class, 'class_room_teachers')->withPivot('joined_at', 'left_at');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function quranQuizzes(): HasMany
    {
        return $this->hasMany(QuranQuiz::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function activity(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class);
    }

    public function scopeAppTeacherWithUser($query)
    {
        return $query->select(
            'id',
            'user_id',
            'wives_count',
            'children_count',
            'marital_status'
        )->with('user:id,property_id,email,first_name,last_name,username,identity_number,phone,gender,birth_date,birth_place,father_name,mother_name,qr_code,blood_type,note,current_address,is_has_disease,disease_name,is_has_treatment,treatment_name,are_there_disease_in_family,family_disease_note,status,image')
            ->with('user.certifications')
            ->with('sessions:id,date,start_at,duration,class_room_id,teacher_id,type')
//            ->with('statuses')
//            ->with('contacts')
//            ->with('addresses')
            ->with('classRooms.grade.property:id,name')
            ->with('classRooms.grade:id,name,property_id')
            ->with(['classRooms' => function ($query) {
                $query->orderBy('joined_at', 'desc');
            }])
            ->with('notes.teacher.user:id,first_name,last_name,image')
            ->with('notes.student.user:id,first_name,last_name,image')
            ->with(['notes' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->with('interviews.teacher.user:id,first_name,last_name,image')
            ->with('interviews.student.user:id,first_name,last_name,image')
            ->with(['interviews' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->with('quizzes.teacher.user:id,first_name,last_name,image')
            ->with('quizzes.student.user:id,first_name,last_name,image')
            ->with(['quizzes' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->with('quranQuizzes.teacher.user:id,first_name,last_name,image')
            ->with('quranQuizzes.student.user:id,first_name,last_name,image')
            ->with(['quranQuizzes' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->with('rates.admin.user:id,first_name,last_name,image');
    }
}
