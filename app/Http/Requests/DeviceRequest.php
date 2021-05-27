<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            "name" => ["bail", "required", "string"],
            "ip" => ["bail", "required", "string"],
            "login" => ["bail", "required", "string"],
            "password" => ["bail", "required", "string"],
            "status" => ["numeric"],
            "active" => ["numeric"]
        ];
    }

    public function attributes()
    {
        return [
            "name" => "Device Name"
        ];
    }
}
