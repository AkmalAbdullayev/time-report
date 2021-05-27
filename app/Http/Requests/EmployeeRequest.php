<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            "first_name" => ['required', 'string', 'min:3'],
            "last_name" => ['required', 'string', 'min:3'],
            "middle_name" => ['nullable', 'string', 'min:3'],
            "phone" => ['required'],
            "tin" => ['required', 'unique:employees,description,NULL,id,deleted_at,NULL'],
            "company" => ['required'],
            "branch" => ['required'],
            "department" => ['required'],
            "position" => ['required'],
            "photo" => ['required', 'mimes:jpg', 'max:200']
        ];
    }

    public function attributes()
    {
        return [
            "first_name" => "Имя",
            "last_name" => "Фамилия",
            "middle_name" => "Отчество",
            "phone" => "Телефон",
            "tin" => "ИНН",
            "company" => "Организация",
            "branch" => "Здание",
            "department" => "Подразделение",
            "position" => "Должность",
            "photo" => "Фото"
        ];
    }

    public function messages()
    {
        return [
            "first_name.required" => ":attribute не должно быть пустым!!!",
            "last_name.required" => ":attribute не должно быть пустым!!!",
            "middle_name.min" => ":attribute не должно быть меньше 3 символа!!!",
            "phone.required" => ":attribute не должно быть пустым!!!",
            "tin.required" => ":attribute не должно быть пустым!!!",
            "tin.unique" => ":attribute уже существует!!!",
            "company.required" => ":attribute не должно быть пустым!!!",
            "branch.required" => ":attribute не должно быть пустым!!!",
            "department.required" => ":attribute не должно быть пустым!!!",
            "position.required" => ":attribute не должно быть пустым!!!",
            "photo.mimes" => "Расширение должно быть .jpg!!!",
            "photo.max" => "Фотография не должно превышать больче чем :max килобайтов!!!"
        ];
    }
}
