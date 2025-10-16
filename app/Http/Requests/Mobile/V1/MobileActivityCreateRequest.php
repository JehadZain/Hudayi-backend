<?php

namespace App\Http\Requests\Mobile\V1;

use Illuminate\Foundation\Http\FormRequest;

class MobileActivityCreateRequest extends FormRequest
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
            'activity_type_id' => '',
            'teacher_id' => '',
            'name' => '',
            'place' => '',
            'cost' => '',
            'result' => '',
            'note' => '',
            'start_datetime' => '',
            'end_datetime' => '',
            'image' => '',

        ];
    }
}
