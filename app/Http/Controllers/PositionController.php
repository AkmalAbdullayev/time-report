<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    protected Position $model;

    public function __construct(Position $position)
    {
        $this->model = $position;
    }

    public function index()
    {
        return view("fp.employees.position");
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "department" => "required"
        ]);

        $this->model->create([
            "name" => $request->input("name"),
            "department_id" => $request->input("department")
        ]);

        return back()->with("message", "Информация добавлено успешно");
    }
}
