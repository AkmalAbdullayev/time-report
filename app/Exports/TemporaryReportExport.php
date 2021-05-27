<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TemporaryReportExport implements FromView
{
    public function __construct($employeeReports)
    {
        $this->employeeReports = $employeeReports;
    }

    public function view(): View
    {
        if ($this->employeeReports){
            return view('fp.report.export.temporary', ['employeeReports' => $this->employeeReports]);
        }
    }
}
