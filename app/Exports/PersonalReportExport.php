<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PersonalReportExport implements FromView
{
    public function __construct($comeOutList)
    {
        $this->comeOutList = $comeOutList;
    }

    public function view(): View
    {
        if ($this->comeOutList){
            return view("fp.report.export.personal", [
                "comeOuts" => $this->comeOutList
            ]);
        }
    }
}
