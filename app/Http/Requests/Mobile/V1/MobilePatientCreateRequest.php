<?php

namespace App\Http\Requests\Mobile\V1;

use Illuminate\Foundation\Http\FormRequest;

class MobilePatientCreateRequest extends FormRequest
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
            // 'disease_name' => 'required|max:255',
            // 'diagnosis_date' => 'required|date',
            // 'medical_report_url' => 'nullable',
            // 'medical_report_image' => 'nullable',

            'user_id' => '',
            'disease_name' => '',
            'diagnosis_date' => '',
            'medical_report_url' => 'nullable',
            'medical_report_image' => 'nullable',
        ];
    }
}
