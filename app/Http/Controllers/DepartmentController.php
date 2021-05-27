<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    protected $model;

    public function __construct(Department $department)
    {
        $this->model = $department;
    }

    public function index()
    {
        return view("fp.employees.department");
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "company" => "required"
        ]);

        $this->model->create([
            "name" => $request->input("name"),
            "company_id" => $request->input("company")
        ]);

        return back()->with("message", "Информация добавлено Успешно");
    }
}
