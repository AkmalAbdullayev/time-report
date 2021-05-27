<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    protected Branch $model;

    public function __construct(Branch $branch)
    {
        $this->model = $branch;
    }

    public function index()
    {
        return view("fp.employees.branch");
    }

    public function create()
    {
        //
    }

    public function store(BranchRequest $request)
    {
        $validatedData = $request->validated();

        $this->model->create([
            "name" => $validatedData["name"],
            "company_id" => $validatedData["company"]
        ]);

        return back()->with("message", "Информация успешно добавлено!");
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
