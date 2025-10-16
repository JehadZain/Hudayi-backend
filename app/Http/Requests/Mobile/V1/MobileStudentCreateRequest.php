<?php

namespace App\Http\Requests\Mobile\V1;

use Illuminate\Foundation\Http\FormRequest;

class MobileStudentCreateRequest extends FormRequest
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
            'user_id' => '',
            'parent_work' => '',
            'family_members_count' => '',
            'status' => '',
            'parent_phone' => '',
            'who_is_parent' => '',
            'is_orphan' => '',
            'user' => '',
            'contact' => '',
            'address' => '',
        ];
    }
}
