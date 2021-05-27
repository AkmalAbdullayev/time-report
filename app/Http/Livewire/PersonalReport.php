<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PersonalReport extends Component
{
    public $employee_id = null, $employeeReports = null;
    public string $date = '';

    public function render()
    {
        $comeOuts = \App\Models\ComeOut::query();

        $comeOut = [];
        $come_out_id = [];
        foreach ($comeOuts as $value) {
            $date = date("Y-m-d", strtotime($value->action_time));
            if ($value->doorDevice->is_come == 1 && !isset($comeOut[$value->employee_id][$date]["in"])) {
                $comeOut[$value->employee_id][$date]["in"] = $value->id;
            } elseif ($value->doorDevice->is_come == 0) {
                $comeOut[$value->employee_id][$date]["out"] = $value->id;
            }
        }

        foreach ($comeOut as $arr) {
            foreach ($arr as $value) {
                $come_out_id[] = ($value['in'] ?? $value['out']);
            }
        }

        $comeOuts = DB::table('come_outs')
            ->whereIn("come_outs.id", $come_out_id)
            ->join("door_device", "come_outs.doors_has_device_id", '=', "door_device.id")
            ->join('employees', 'come_outs.employee_id', '=', 'employees.id')
            ->join('companies', 'employees.company_id', '=', 'companies.id')
            ->join('branches', 'employees.branch_id', '=', 'branches.id')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->join('schedules', 'employees.schedule_id', '=', 'schedules.id')
            ->select([
                "employees.first_name as first_name",
                "employees.last_name as last_name",
                "employees.id as employee_id",
                "companies.name as company_name",
                "branches.name as branch_name",
                "departments.name as department_name",
                "positions.name as position_name",
                "schedules.name as schedule_name",
                "come_outs.action_time as action_time"
            ])
            ->orderBy("come_outs.action_time", "desc")
            ->paginate(10);

        if (!empty($this->date)) {
            $this->check($comeOuts);
        }

        return view('livewire.personal-report', [
            "employees" => Employee::all(),
            "companies" => Company::all(),
            "comeOuts" => $comeOuts
        ]);
    }

    public function check($comeOuts)
    {
        $comeOuts = $comeOuts->when(!is_null($this->date), function ($query) {
            $date = explode("-", $this->date);
            $from = date('Y-m-d', strtotime(trim($date[0])));
            $to = date('Y-m-d', strtotime(trim($date[1]) . "+ 1 day"));

            return $query->whereBetween("come_outs.action_time", [$from, $to]);

//            $query->with(["branches", "departments", "positions", "companies", "comeOuts"])
//                ->whereHas("comeOuts", function ($query) use ($from, $to) {
//                    return $query->whereBetween("action_time", [$from, $to]);
//                });
        });
    }
}
