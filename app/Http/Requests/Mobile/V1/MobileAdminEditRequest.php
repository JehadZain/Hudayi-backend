<?php

namespace App\Http\Requests\Mobile\V1;

use Illuminate\Foundation\Http\FormRequest;

class MobileAdminEditRequest extends FormRequest
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
            //             'user_id' => 'required',
            //             'wives_count' => 'nullable|numeric|max:2',
            //             'children_count' => 'nullable|numeric|max:2',

            'user_id' => '',
            'wives_count' => '',
            'children_count' => '',
            'job_title_id' => '',
            'marital_status' => '',
            'user' => '',
            'contact' => '',
            'address' => '',
            'jobTitle' => '',
        ];
    }
}
