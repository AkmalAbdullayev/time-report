<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
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
            "name" => 'required|string',
            'range_from' => 'required|string',
            'range_to' => 'required|string',
            'description' => 'string',
            'type' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "Название графика не должно быть пустым.",
            "range_from.required" => "Интервал не должно быть пустым.",
            "range_to.required" => "Интервал не должно быть пустым."
        ];
    }
}
