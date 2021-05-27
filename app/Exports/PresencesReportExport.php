<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PresencesReportExport implements FromView
{
    public function __construct($currentlyIn)
    {
        $this->currentlyIn = $currentlyIn;
    }

    public function view(): View
    {
        if ($this->currentlyIn){
            return view("fp.report.export.presences", [
                "currentlyIn" => $this->currentlyIn
            ]);
        }
    }
}
