<?php

namespace App\Http\Requests\App\V1;

use Illuminate\Foundation\Http\FormRequest;

class AppAddressEditRequest extends FormRequest
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
            'addressable_type' => '',
            'addressable_id' => '',
            'label' => '',
            'country' => '',
            'city' => '',
            'state' => '',
            'line_1' => '',
            'line_2' => '',
            'floor' => '',
            'flat' => '',
            'lat' => '',
            'long' => '',
            'location_url' => '',
        ];
    }
}
