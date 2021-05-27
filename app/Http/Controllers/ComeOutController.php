<?php

namespace App\Http\Controllers;

use App\helpers\AttendanceManagement;
use App\Models\ComeOut;
use App\Models\ComeOutLog;
use App\Models\Company;
use App\Models\Door;
use App\Models\DoorDevice;
use App\Models\Employee;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComeOutController extends Controller
{
    private AttendanceManagement $attendanceManagement;

    public function __construct()
    {
        $this->attendanceManagement = new AttendanceManagement();
    }

    public function index()
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


        return view("fp.come-out.index", [
            "companies" => Company::all(),
            "comeOuts" => $comeOuts,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'door_id' => 'required|integer|exists:doors,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'action_time' => 'required|date'
        ]);

        try {
            ComeOut::create([
                'doors_has_device_id' => DoorDevice::where('doors_id', $request->door_id)->first()->id,
                'employee_id' => $request->employee_id,
                'action_time' => date('Y-m-d H:i:s', strtotime($request->action_time)),
                'event_serial_no' => hexdec(uniqid()),
                'user_id' => auth()->id()
            ]);

            ComeOutLog::create([
                'user_id' => auth()->id(),
                'type' => 1
            ]);
            return back()->with('success', 'Добавлено Успешно');
        }catch (\Exception $exception){
            return back()->with('danger', 'Упс! Что-то пошло не так. Пожалуйста, попробуйте еще раз!');
        }
    }

    public function edit($id)
    {
        $comeOut = ComeOut::where('id', $id)->with(['doorDevice', 'employees' => function ($query) {
            $query->withoutGlobalScope(SoftDeletingScope::class);
        }])->firstOrFail();
        $employees = Employee::select('id', 'first_name', 'last_name', 'middle_name')->get();
        $doors = Door::select('id', 'name')->get();

        return view('fp.come-out.edit', compact('comeOut', 'employees', 'doors'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'door_id' => 'required|integer|exists:doors,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'action_time' => 'required|date'
        ]);

        $comeOut = ComeOut::findOrFail($request->id);

        try {
            $comeOut->update([
                'doors_has_device_id' => DoorDevice::where('doors_id', $request->door_id)->first()->id,
                'employee_id' => $request->employee_id,
                'action_time' => date('Y-m-d H:i:s', strtotime($request->action_time))
            ]);

            ComeOutLog::create([
                'user_id' => auth()->id(),
                'type' => 2
            ]);
            return back()->with('success', 'Добавлено Успешно');
        }catch (\Exception $exception){
            return back()->with('danger', 'Упс! Что-то пошло не так. Пожалуйста, попробуйте еще раз!');
        }
    }

    public function delete(Request $request)
    {
        $comeOut = ComeOut::findOrFail($request->id);
        try {
            $comeOut->delete();

            return back()->with('success', 'Успешно удален');
        }catch (\Exception $exception){
            return back()->with('danger', 'Упс! Что-то пошло не так. Пожалуйста, попробуйте еще раз!');
        }
    }
}
