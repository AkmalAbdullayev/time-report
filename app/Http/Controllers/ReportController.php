<?php

namespace App\Http\Controllers;

use App\Exports\PersonalReportExport;
use App\Exports\PersonalTodayReportExport;
use App\Exports\PresencesReportExport;
use App\Exports\TemporaryReportExport;
use App\helpers\AttendanceManagement;
use App\Models\ComeOut;
use App\Models\Company;
use App\Models\Department;
use App\Models\Door;
use App\Models\Employee;
use App\Models\ManualComeOut;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private ManualComeOut $manualComeOut;
    public string $tab = "default";

    public function __construct(ManualComeOut $manualComeOut)
    {
        $this->manualComeOut = $manualComeOut;
    }

    public function full()
    {
        return view("fp.report.full", [
            "companies" => Company::all(),
            "comeOuts" => $this->comeOutList(),
            "employees" => Employee::all()
        ]);
    }

    public function personal()
    {
        if (\Request::route()->getName() == 'exportPersonalReport') {
            $comeOutList = $this->comeOutList(false);
            $exp = new PersonalReportExport($comeOutList);
            return Excel::download($exp, "personal_report_" . date('H:i:s') . ".xlsx");
        }
        if (\Request::route()->getName() == 'exportPersonalReportToday') {
            $employees = Employee::all();
            $exp = new PersonalTodayReportExport($employees);
            return Excel::download($exp, "personal_today_report_" . date('H:i:s') . ".xlsx");
        }

        return view("fp.report.personal", [
            "companies" => Company::all(),
            "departments" => Department::all(),
            "comeOuts" => $this->comeOutList(),
            "employees" => Employee::all(),
            "tab" => $this->tab
        ]);
    }

    public function uniqueComeOut()
    {
        $comeOut = [];
        $comeOutsBetweenDate = ComeOut::with("doorDevice")->whereDate("action_time", now()->format("Y-m-d"))->orderBy("action_time")->get();

        foreach ($comeOutsBetweenDate as $value) {
            $date = date("Y-m-d", strtotime($value->action_time));
            if ($value->doorDevice->is_come == 1 && !isset($comeOut[$value->employee_id][$date]["in"])) {
                $comeOut[$value->employee_id][$date]["in"] = $value->id;
            } elseif ($value->doorDevice->is_come == 0) {
                $comeOut[$value->employee_id][$date]["out"] = $value->id;
            }
        }

        $come_out_id = [];
        foreach ($comeOut as $arr) {
            foreach ($arr as $value) {
                $come_out_id[] = ($value['in'] ?? $value['out']);
            }
        }

        return $come_out_id;
    }

    public function comeOutList($paginate = true)
    {
        $come_out_id = $this->uniqueComeOut();

        $comeOuts = DB::table('come_outs')
            ->whereIn('come_outs.id', $come_out_id)
            ->join("door_device", "come_outs.doors_has_device_id", '=', "door_device.id")
            ->join('employees', 'come_outs.employee_id', '=', 'employees.id')
            ->join('companies', 'employees.company_id', '=', 'companies.id')
            ->join('branches', 'employees.branch_id', '=', 'branches.id')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->join('schedules', 'employees.schedule_id', '=', 'schedules.id');

        $this->filter($comeOuts);

        if (request()->filled("absent_page") || request()->filled(["company_filter", "department_filter"])) {
            $this->tab = "absent";
        }

        $comeOuts = $comeOuts->orderBy("come_outs.action_time", "desc")
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
            );
        if ($paginate === false)
            return $comeOuts->get();
        else
            return $comeOuts->paginate(10);
    }

    public function manual()
    {
        return view("fp.report.manual");
    }

    public function storeManual(Request $request)
    {
        $this->manualComeOut->create([
            "employees_id" => $request->input("employee"),
            "users_id" => auth()->id(),
            "come_at" => $request->input("come"),
            "left_at" => $request->input("left"),
            "reason" => $request->input("reason"),
            "description" => $request->input("description")
        ]);

        return back();
    }

    public function deleteManual($id)
    {
        $this->manualComeOut->findOrFail($id)->delete();

        return back();
    }

    public function filter($comeOuts)
    {
        $comeOuts = $comeOuts->when(request("attendance_status") == "1", function ($query) {
            return $query->where("door_device.is_come", "=", "1")
                ->where("range_from", '<=', DB::raw("(DATE_FORMAT(action_time, '%H:%i:%s'))"));
        });
        $comeOuts = $comeOuts->when(request("attendance_status") == "2", function ($query) {
            return $query->where("door_device.is_come", "=", "1")
                ->where("range_from", ">=", DB::raw("(DATE_FORMAT(action_time, '%H:%i:%s'))"));
        });
        $comeOuts = $comeOuts->when(request("attendance_status") == "3", function ($query) {
            return $query->where("door_device.is_come", "=", "0")
                ->where("range_to", "<", DB::raw("(DATE_FORMAT(action_time, '%H:%i:%s'))"));
        });
        $comeOuts = $comeOuts->when(request("attendance_status") == "4", function ($query) {
            return $query->where("door_device.is_come", "=", "0")
                ->where("range_to", ">", DB::raw("(DATE_FORMAT(action_time, '%H:%i:%s'))"));
        });

        $comeOuts = $comeOuts->when(request()->filled(["employees", "date"]), function ($query) {
            $date = explode("-", request("date"));
            $from = date('Y-m-d', strtotime(trim($date[0])));
            $to = date('Y-m-d', strtotime(trim($date[1])));
            return $query->where("come_outs.employee_id", request("employees"))
                ->whereDate('come_outs.action_time', '>=', $from)
                ->whereDate('come_outs.action_time', '<=', $to);
        });

        $comeOuts = $comeOuts->when(request()->filled("company"), function ($query) {
            return $query->where("employees.company_id", "=", request("company"));
        });

        $comeOuts = $comeOuts->when(request()->filled("department"), function ($query) {
            return $query->where("employees.department_id", "=", request("department"));
        });

//        if (request()->filled("date")) {
//            $this->dateFilter($comeOuts);
//        }

//        $this->companyFilter($comeOuts);
    }

    public function dateFilter($comeOuts)
    {
        $date = explode("-", request("date"));
        $from = date('Y-m-d', strtotime(trim($date[0])));
        $to = date('Y-m-d', strtotime(trim($date[1])));
        $comeOuts->where('come_outs.action_time', '>=', $from)
            ->whereDate('come_outs.action_time', '<=', $to);
    }

    public function companyFilter($comeOuts)
    {
        if (request()->filled("company_id")) {
            $comeOuts->where("employees.company_id", "=", request("company_id"));
        }
    }

    public function temporary(Request $request)
    {
        $query = Employee::query();
        $query->with(["companies", "branches", "departments", "positions", "comeOuts" => function ($query) {
            $query->whereBetween("action_time", [now()->year . "-" . now()->format("m") . "-01", now()->addDay()->format("Y-m-d")])
                ->with('doorDevice')->orderBy('action_time', 'desc');
        }]);

        $query->when($request->filled(["employees", "date"]), function ($q) use ($request) {
            $date = explode("-", request("date"));
            $from = date('Y-m-d', strtotime(trim($date[0])));
            $to = date('Y-m-d', strtotime(trim($date[1]) . "+ 1 day"));
            $q->with(["companies", "branches", "departments", "positions", "comeOuts" => function ($q) use ($from, $to) {
                $q->whereBetween("action_time", [$from, $to])->with('doorDevice')->orderBy('action_time', 'desc');
            }])->where("id", $request->input("employees"));
        });

        $query->when($request->filled("date"), function ($q) use ($request) {
            $date = explode("-", request("date"));
            $from = date('Y-m-d', strtotime(trim($date[0])));
            $to = date('Y-m-d', strtotime(trim($date[1]) . "+ 1 day"));
            $q->with(["companies", "branches", "departments", "positions", "comeOuts" => function ($q) use ($from, $to) {
                $q->whereBetween("action_time", [$from, $to])->with('doorDevice')->orderBy('action_time', 'desc');
            }]);
        });

        $query->when($request->filled(["date", "company"]), function ($q) use ($request) {
            $date = explode("-", request("date"));
            $from = date('Y-m-d', strtotime(trim($date[0])));
            $to = date('Y-m-d', strtotime(trim($date[1]) . "+ 1 day"));
            $q->with(["branches", "departments", "positions", "companies", "comeOuts" => function ($query) use ($from, $to) {
                $query->whereBetween("action_time", [$from, $to])->with('doorDevice')->orderBy('action_time', 'desc');
            }])
                ->whereHas("companies", function ($q) use ($request) {
                    return $q->where("id", $request->input("company"));
                });
        });

        $query->when($request->filled(["date", "company", "department"]), function ($q) use ($request) {
            $date = explode("-", request("date"));
            $from = date('Y-m-d', strtotime(trim($date[0])));
            $to = date('Y-m-d', strtotime(trim($date[1]) . "+ 1 day"));
            $q->with(["branches", "departments", "positions", "companies", "comeOuts" => function ($query) use ($from, $to) {
                $query->whereBetween("action_time", [$from, $to])->with('doorDevice')->orderBy('action_time', 'desc');
            }])->whereHas("companies", function ($q) use ($request) {
                    return $q->where("id", $request->input("company"));
                })->whereHas("departments", function ($q) use ($request) {
                    return $q->where("id", $request->input("department"));
                });
        });


        $date = (request()->has("date") && request("date")) != '' ? explode("-", request("date")) : [now()->year . "-" . now()->format("m") . "-01", now()->addDay()->format("Y-m-d")];
        $from = date('Y-m-d', strtotime(trim($date[0])));
        $to = date('Y-m-d', strtotime(trim($date[1]) . "+ 1 day"));
        $normDay = Employee::normDay($from, $to);

        if (\Request::route()->getName() == 'exportTemporaryReport') {
            $employeeReports = $query->get();
            $exp = new TemporaryReportExport($employeeReports);
            return Excel::download($exp, "temporary_report_" . date('H:i:s') . ".xlsx");
        }

        $employeeReports = $query->get();

        if (request('day_count') == 'desc')
            $employeeReports = $employeeReports->sortByDesc('day_count');
        elseif (request('day_count') == 'asc')
            $employeeReports = $employeeReports->sortBy('day_count');
        elseif (request('work_time') == 'desc')
            $employeeReports = $employeeReports->sortByDesc('work_time');
        elseif (request('work_time') == 'asc')
            $employeeReports = $employeeReports->sortBy('work_time');
        else
            $employeeReports = $employeeReports->sortBy('first_name');

        $employeeReports = $this->paginate($employeeReports)->appends(['company' => request('company'), 'department' => request('department'), 'date' => request('date'), 'day_count' => request('day_count'), 'work_time' => request('work_time')]);

        return view("fp.report.temporary", [
            "employees" => Employee::all(),
            "employeeReports" => $employeeReports,
            "companies" => Company::all(),
            "departments" => Department::all(),
            'normDay' => $normDay
        ]);
    }

    public function paginate($items, $perPage = 10, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, ['path' => route('home') . '/report/temporary']);
    }

    public function presences()
    {
        if (\Request::route()->getName() == 'exportPresencesReport') {
            $query = Employee::query();
            $query->with(["schedule", "companies", "branches", "departments", "positions", "comeOuts" => function ($query) {
                return $query->with("doorDevice")->whereDate("action_time", now()->format("Y-m-d"))->latest("action_time");
            }])->has("comeOuts");

            $currentlyIn = $query->get();

            $exp = new PresencesReportExport($currentlyIn);
            return Excel::download($exp, "presences_report_" . date('H:i:s') . ".xlsx");
        } else
            return view("fp.report.presences", ['export' => false]);
    }

    public function personal2()
    {
        return view("fp.report.personal2");
    }

    public function comeOut(Request $request)
    {
        $comeOuts = new ComeOut;

        if (request()->hasAny(["date", "employee"]) && request('employee') != 0) {
            $date = explode("-", request("date"));
            $from = date('Y-m-d', strtotime(trim($date[0])));
            $to = date('Y-m-d', strtotime(trim($date[1]) . "+ 1 day"));
            $comeOuts = $comeOuts->whereBetween("action_time", [$from, $to])
                ->where("employee_id", request("employee"));
        }

        $comeOuts = $comeOuts->with(['doorDevice', 'employees' => function ($query) {
            $query->withoutGlobalScope(SoftDeletingScope::class);
        }])->orderBy('action_time', 'desc')->paginate(20);
        $employees = Employee::select('id', 'first_name', 'last_name', 'middle_name')->get();
        $doors = Door::select('id', 'name')->get();

        return view('fp.report.come-out', compact('comeOuts', 'employees', 'doors'));
    }

    public function inOffice()
    {
        $online = ComeOut::whereDate('action_time', '=', '2021-05-17')
            ->with(['employees' => function($query) {
                $query->withOutGlobalScope(SoftDeletingScope::class);
            }])->orderBy('action_time', 'desc')
            ->get()
            ->groupBy('employee_id')
            ->where('employee_id', '!=', 37);
        dd($online);
        $employees = Employee::select('id', 'first_name', 'last_name', 'middle_name')->get();

        return view('fp.report.in-office', compact('online', 'employees'));
    }
}
