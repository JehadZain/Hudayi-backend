<?php

namespace App\Http\Requests\Mobile\V1;

use Illuminate\Foundation\Http\FormRequest;

class MobileRateCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'admin_id' => '',
            'teacher_id' => '',
            'student_count'=>'',
            'date' => '',
            'start_date' => '',
            'end_date' => '',
            'correct_reading_skill' => '',
            'teaching_skill' => '',
            'academic_skill' => '',
            'following_skill' => '',
            'plan_commitment' => '',
            'time_commitment' => '',
            'student_commitment' => '',
            'activity' => '',
            'commitment_to_administrative_instructions' => '',
            'exam_and_quizzes' => '',
            'percentage' => '',
            'score' => '',
            'note' => '',
        ];
    }
}
