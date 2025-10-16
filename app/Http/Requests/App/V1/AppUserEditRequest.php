<?php

namespace App\Http\Requests\App\V1;

use Illuminate\Foundation\Http\FormRequest;

class AppUserEditRequest extends FormRequest
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
            // "username"=>"",
            // "email"=>"nullable|email",
            // "password"=>"",
            // "first_name"=>"required|max:255",
            // "last_name"=>"required|max:255",
            // "identity_number"=>"required|numeric|max:20",
            // "phone"=>"required|numeric|max:12",
            // "gender"=>"required|max:255",
            // "birth_date"=>"required|date",
            // "birth_place"=>"required|max:255",
            // "personal_image"=>"",
            // "father_name"=>"required|max:255",
            // "mother_name"=>"required|max:255",
            // "qr_code"=>"",
            // "blood_type"=>"nullable|max:255",
            // "note"=>"nullable|max:500"

            'username' => '',
            'email' => '',
            'password' => '',
            'first_name' => '',
            'last_name' => '',
            'identity_number' => '',
            'phone' => '',
            'gender' => '',
            'birth_date' => '',
            'birth_place' => '',
            'father_name' => '',
            'mother_name' => '',
            'qr_code' => '',
            'blood_type' => '',
            'note' => '',
            'current_address' => '',
            'is_has_disease' => '',
            'disease_name' => '',
            'is_has_treatment' => '',
            'treatment_name' => '',
            'are_there_disease_in_family' => '',
            'family_disease_note' => '',
            'status' => '',
            'property_id' => '',
            'image' => '',
            'is_approved' => '',

        ];
    }
}
