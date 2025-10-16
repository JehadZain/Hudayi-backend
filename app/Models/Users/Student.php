<?php

namespace App\Models\Users;

use App\Models\Interview;
use App\Models\Note;
use App\Models\Participant;
use App\Models\Properties\ClassRoom;
use App\Models\Quiz;
use App\Models\QuranQuiz;
use App\Models\SessionAttendance;
use App\Traits\Morphs\Addressable;
use App\Traits\Morphs\Attendanceable;
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

class Student extends Model
{
    use HasFactory,
        BelongsToUser,
        Attendanceable,
        Referenceable,
        Scoreable,
        SoftDeletes,
        //        Statusable,
        Addressable,
        Contactable;

    //    protected function makeAllSearchableUsing($query)
    //    {
    //        return $query;
    //    }

//    public $quickSearchableArray = ['name', 'id'];

    protected $fillable = [
        'parent_work',
        'family_members_count',
        'parent_phone',
        'who_is_parent',
        'property_id',
        'is_orphan',
    ];

    public function classRooms(): BelongsToMany
    {
        return $this->belongsToMany(ClassRoom::class, 'class_room_students')->withPivot('joined_at', 'left_at', 'deleted_at')
            ->whereNull('class_room_students.deleted_at');
    }

    public function sessionAttendances(): HasMany
    {
        return $this->hasMany(SessionAttendance::class);
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

    public function activityParticipants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function scopeAppStudentWithUser($query)
    {
        return $query->select(
            'id',
            'user_id',
            'parent_work',
            'family_members_count',
            'is_orphan',
            'parent_phone',
            'who_is_parent',
        )->with('user:id,property_id,email,first_name,last_name,username,identity_number,phone,gender,birth_date,birth_place,father_name,mother_name,qr_code,blood_type,note,current_address,is_has_disease,disease_name,is_has_treatment,treatment_name,are_there_disease_in_family,family_disease_note,status,image')
            ->with('user.certifications')
//            ->with('statuses')
//            ->with('contacts')
//            ->with('addresses')
//            ->with('attendances')
//            ->with('scores')
//            ->with('classRooms:id,grade_id,name')
            ->with('classRooms.grade:id,name')
            ->with('sessionAttendances.session')
            ->with('activityParticipants.activity.activityType')
            ->with('activityParticipants.activity.participants.student.user:id,first_name,last_name,image')
            ->with('notes.teacher.user:id,first_name,last_name,image')
            ->with('notes.admin.user:id,first_name,last_name,image')
            ->with('interviews.teacher.user:id,first_name,last_name')
            ->with(['quranQuizzes' => function ($query) {
                $query->orderBy('id', 'desc'); // Order the quranQuizzes records in descending order by their ID
            }])
            ->with('quranQuizzes.teacher.user:id,first_name,last_name')
            ->with('quizzes.teacher.user:id,first_name,last_name');
        //            ->with('attendances.session');
    }
}
