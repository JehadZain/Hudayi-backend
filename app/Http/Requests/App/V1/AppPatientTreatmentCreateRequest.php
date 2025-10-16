<?php

namespace App\Http\Requests\App\V1;

use Illuminate\Foundation\Http\FormRequest;

class AppPatientTreatmentCreateRequest extends FormRequest
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
            // 'patient_id' => "required|exists:patients,id",
            // 'treatment_name' => "required|max:255",
            // 'treatment_cost' => "required|numeric|max:4",
            // 'availability' => "",
            // 'schedule' => "",

            'patient_id' => '',
            'treatment_name' => '',
            'treatment_cost' => '',
            'availability' => '',
            'schedule' => '',
        ];
    }
}
