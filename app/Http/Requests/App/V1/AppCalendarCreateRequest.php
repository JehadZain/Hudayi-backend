<?php

namespace App\Http\Requests\App\V1;

use Illuminate\Foundation\Http\FormRequest;

class AppCalendarCreateRequest extends FormRequest
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
            'class_room_id' => '',
            'day_name' => '',
            'subject_name' => '',
            'start_at' => '',
            'end_at' => '',
        ];
    }
}
