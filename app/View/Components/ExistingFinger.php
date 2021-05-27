<?php

namespace App\View\Components;

use App\Models\Employee;
use App\Models\EmployeeDoor;
use App\Models\EmployeeFinger;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ExistingFinger extends Component
{
    public $model, $deviceId;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($model, $deviceId)
    {
        $this->model = $model;
        $this->deviceId = $deviceId;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */

    public function render()
    {
        $fingers = EmployeeFinger::where("employee_id", $this->model->id)->orderBy("name", "desc")->get();
        $employeeDevices = EmployeeDoor::with(['doorDevice' => function ($q) {
            $q->with('device');
        }])->whereNotNull('door_device_id')->where('employee_id', $this->model->id)->get();
        return view('components.existing-finger', compact("fingers", "employeeDevices"));
    }
}
