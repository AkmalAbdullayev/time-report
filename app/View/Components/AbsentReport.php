<?php

namespace App\View\Components;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\View\Component;

class AbsentReport extends Component
{
    public $companies;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->companies = Company::all();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $employees = Employee::absent();

        $employees->when(\request()->filled(["company_filter", "department_filter"]), function ($query) {
            return $query->where("companies.id", "=", \request("company_filter"))
                ->where("departments.id", "=", \request("department_filter"));
        });

        $employees = $employees->paginate(10, ['*'], 'absent_page');

        return view('components.absent-report', [
            "employees" => $employees
        ]);
    }
}
