<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;

class AjaxController extends Controller
{
    /**
     * @param $departmentId
     * @return mixed
     */
    public function positions($departmentId)
    {
        return Position::where('department_id', $departmentId)->orderBy('name')->pluck('id', 'name');
    }

    /**
     * @param $companyId
     * @return mixed
     */
    public function branches($companyId)
    {
        return Branch::where('company_id', $companyId)->orderBy('name')->pluck('id', 'name');
    }

    /**
     * @param $companyId
     * @return mixed
     */
    public function departments($companyId)
    {
        return Department::where('company_id', $companyId)->orderBy('name')->pluck('id', 'name');
    }
}
