<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public $model;

    public function __construct(Company $department)
    {
        $this->model = $department;
    }

    public function index()
    {
        return view("fp.employees.company");
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Company $department)
    {
        //
    }

    public function edit(Company $department)
    {
        //
    }

    public function update(Request $request, Company $department)
    {
        //
    }

    public function destroy(Company $department)
    {
        //
    }
}
