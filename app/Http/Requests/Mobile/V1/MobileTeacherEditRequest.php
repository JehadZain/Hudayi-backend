<?php

namespace App\Http\Requests\Mobile\V1;

use Illuminate\Foundation\Http\FormRequest;

class MobileTeacherEditRequest extends FormRequest
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
            // 'user_id' => 'required|exists:users,id',
            // 'wives_count' => 'required|numeric|max:2',
            // 'children_count' => 'required|numeric|max:4',
            'user_id' => '',
            'wives_count' => '',
            'children_count' => '',
            'marital_status' => '',
            'user' => '',
            'contact' => '',
            'address' => '',
        ];
    }
}
