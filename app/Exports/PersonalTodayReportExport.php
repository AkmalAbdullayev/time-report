<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PersonalTodayReportExport implements FromView
{
    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    public function view(): View
    {
        if ($this->employees){
            return view("fp.report.export.personal-today", [
                "employees" => $this->employees
            ]);
        }
    }
}
