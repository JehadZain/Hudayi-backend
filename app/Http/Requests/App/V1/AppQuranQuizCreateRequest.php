<?php

namespace App\Http\Requests\App\V1;

use Illuminate\Foundation\Http\FormRequest;

class AppQuranQuizCreateRequest extends FormRequest
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
            'id' => '',
            'name' => '',
            'juz' => '',
            'page' => '',
            'date' => '',
            'exam_type' => '',
            'score' => '',
            'student_id' => '',
            'teacher_id' => '',
        ];
    }
}
