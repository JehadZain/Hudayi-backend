<?php

namespace App\Http\Requests\Mobile\V1;

use Illuminate\Foundation\Http\FormRequest;

class MobileCertificationEditRequest extends FormRequest
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
            // 'name' => 'required|max:255',
            // 'link' => 'nullable',
            // 'qr_code' => 'nullable',
            // 'issuing_date' => 'nullable|date',
            // 'expiration_date' => 'nullable|date',

            'user_id' => '',
            'name' => '',
            'image' => '',
            'issuing_date' => '',
            'expiration_date' => '',
            'certificate_type' => '',
        ];
    }
}
