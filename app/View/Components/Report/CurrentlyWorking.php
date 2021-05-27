<?php

namespace App\View\Components\Report;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\View\Component;

class CurrentlyWorking extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.report.currently-working');
    }
}
