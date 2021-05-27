<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Live extends Component
{
    public function render()
    {
        $comeOuts = DB::table('come_outs')
            ->join("door_device", "come_outs.doors_has_device_id", '=', "door_device.id")
            ->join('employees', 'come_outs.employee_id', '=', 'employees.id')
            ->join('companies', 'employees.company_id', '=', 'companies.id')
            ->join('branches', 'employees.branch_id', '=', 'branches.id')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->join('schedules', 'employees.schedule_id', '=', 'schedules.id');

        if (request()->filled('attendance_status')) {
            switch (request('attendance_status')) {
                case 1:
                    $comeOuts = $comeOuts->where("door_device.is_come", '=', '1')
                        ->where('range_from', '<=', DB::raw("(DATE_FORMAT(action_time, '%H:%i'))"));
                    break;
                case 2:
                    $comeOuts = $comeOuts->where("door_device.is_come", '=', '1')
                        ->where('range_from', '>=', DB::raw("(DATE_FORMAT(action_time, '%H:%i'))"));
                    break;
                case 3:
                    $comeOuts = $comeOuts->where("door_device.is_come", '=', '2')
                        ->where('range_to', '<', DB::raw("(DATE_FORMAT(action_time, '%H:%i'))"));
                    break;
                case 4:
                    $comeOuts = $comeOuts->where("door_device.is_come", '=', '2')
                        ->where('range_to', '>', DB::raw("(DATE_FORMAT(action_time, '%H:%i'))"));
                    break;
            }
        }

        $comeOuts = $comeOuts->where("action_time", "LIKE", "%" . now()->format("y-m") . "%")
            ->orderBy("come_outs.action_time", "desc")
            ->select(
                "come_outs.id as id",
                "come_outs.action_time as action_time",
                "employees.first_name as first_name",
                "employees.last_name as last_name",
                "schedules.name as schedule_name",
                "companies.name as company_name",
                "branches.name as branch_name",
                "departments.name as department_name",
                "positions.name as position_name",
                "come_outs.doors_has_device_id as doors_has_device_id",
                "schedules.range_from as range_from",
                "schedules.range_to as range_to",
                "employees.id as employee_id",
                "door_device.is_come as is_come"
            )
            ->paginate(10);

        $companies = \App\Models\Company::all();

        return view('livewire.live', compact('comeOuts', 'companies'));
    }
}
