<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'employee_id' => ['required', 'numeric'],
            'guest_name' => ['nullable', 'string', 'min:3'],
            'type' => ['nullable', 'string'],
            'description' => ['nullable', 'string', 'min:3']
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'Поля не должно быть пустым.',
            'guest_name.min' => 'Поля не должно быть меньше 3 символа!!!',
            'description.min' => 'Поля не должно быть меньше 3 символа!!!'
        ];
    }
}
