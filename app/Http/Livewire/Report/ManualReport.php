<?php

namespace App\Http\Livewire\Report;

use App\Models\DoorDevice;
use App\Models\Employee;
use Livewire\Component;

class ManualReport extends Component
{
    public $employees;
    public $doors;

    public $employee_id = null;
    public $doorDeviceId = null;
    public $date = null;
    public $time = null;

    public function mount()
    {
        $this->employees = Employee::all();
        $this->doors = DoorDevice::with(["device", "doors"])->get();
    }

    public function render()
    {
        return view('livewire.report.manual-report');
    }

    public function store()
    {
        dd($this->time);
    }
}
