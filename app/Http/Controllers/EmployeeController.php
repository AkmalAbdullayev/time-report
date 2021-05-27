<?php

namespace App\Http\Controllers;

use App\helpers\UserManagement;
use App\Http\Requests\EmployeeRequest;
use App\Jobs\Device\AddFinger;
use App\Jobs\Device\AddUser;
use App\Models\Company;
use App\Models\CronTask;
use App\Models\Device;
use App\Models\DoorDevice;
use App\Models\Employee;
use App\Models\EmployeeDoor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public Employee $model;
    private UserManagement $user_management;
    protected EmployeeDoor $employeeDoor;
    private string $pageName = "employees";

    public function __construct(Employee $model)
    {
        $this->model = $model;
        $this->user_management = new UserManagement();
        $this->employeeDoor = new EmployeeDoor();
    }

    public function index()
    {
        $employees = Employee::all();
        $errorTasks = CronTask::where("status", 3)->count();
        return view("fp.employees.index", [
            "employees" => $employees,
            "errorTasks" => $errorTasks
        ]);
    }

    public function create()
    {
        abort(404);
    }

    public function store(EmployeeRequest $request)
    {
        $request->validated();

        DB::transaction(function () use ($request) {
            $filename = null;

            $company = Company::findOrFail($request->input("company"));

            if ($request->hasFile("photo")) {
                $file = $request->file("photo");
                $fileExtension = $file->getClientOriginalExtension();
                $filename = $request->input("first_name") . "_" . $request->input("last_name") . ".{$fileExtension}";
                $file->storeAs("{$company->id}/", $filename);
            }

            $model = $this->model->create([
                "first_name" => request("first_name"),
                "last_name" => request("last_name"),
                "middle_name" => request("middle_name"),
                "image" => $filename !== null ? "{$company->id}/$filename" : null,
                "phone" => str_replace([" ", '-', '(', ')', '_', '+'], "", request("phone")),
                "description" => request("tin"),
                "is_fired" => 0,
                "company_id" => request("company"),
                "branch_id" => request("branch"),
                "department_id" => request("department"),
                "position_id" => request("position"),
                "telegram_id" => request("telegram_id")
            ]);

            $door_devices = DoorDevice::whereIn("doors_id", request("door"))->get();

//            $devices = [];
            foreach ($door_devices as $door) {
                $this->employeeDoor->create([
                    "employee_id" => $model->id,
                    "doors_id" => $door->doors_id,
                    "door_device_id" => $door->id,
                    "employee_device_status" => 0,
                    "employee_finger_status" => 0,
                    "added_at" => now()
                ]);
//                $devices[] = Device::find($door->device_id);
            }

            AddUser::dispatch($model);

//            foreach ($devices as $device) {
//                if ($device->checkDeviceStatus(true)) {
//                    $status = $this->user_management->addUser($model);
//                    if ($request->hasFile("photo")) {
//                        $this->photo($model);
//                    }
//
//                    if (isset($status["statusCode"])) {
//                        session()->flash("success", "Сотрудник добавлено успешно");
//                        return back();
//                    } else {
//                        session()->flash("error", "Ошибка при добавлении сотрудника в устройство");
//                    }
//                } else {
//                    $log = new Log();
//                    $task = [
//                        "key" => "employee_door",
//                        "action" => "insert",
//                        "value" => $model->id,
//                    ];
//                    $description = "Ошибка при добавлении сотрудника в устройство. Устройство не активно!!!";
//                    session()->flash("error", "1 Новое сообщение!!!");
//                    $log->create($task, 0, $description);
//                }
//            }
        });
        return back();
    }

    public function photo($model)
    {
        $photo = asset("storage/{$model->image}");
        $status = $this->user_management->bindPhoto($model, $photo);
    }

    public function position()
    {
        return view("fp.employees.position");
    }

    public function schedule()
    {
        return view("fp.employees.schedule");
    }

    /**
     * @param $id
     * @param $door
     * @return RedirectResponse
     */
//    public function addEmployee($id, $door)
//    {
//        $employee = $this->employeeDoor->where("employee_id", $id)->where("doors_id", $door)->first();
//        $getDevice = DoorDevice::where("doors_id", $door)->get();
//        $status = $this->user_management->addUser($employee, array_unique($device_ip));
//
//        if (isset($status["statusCode"])) {
//            if ($status["statusCode"] === 1) {
//                $employee = $this->employeeDoor->where("employee_id", $id)->first();
//                $employee->employee_device_status = 1;
//                $employee->save();
//                return back()->with('successMessage', 'Добавлено Успешно');
//            }
//        } else {
//            session()->flash("errorMessage", "Ошибка при добавлении сотрудника");
//        }
//    }

    /**
     * @param $employee_id
     * @param string $type
     * @param string $deviceId
     * @return RedirectResponse
     */
    public function addFingerPrint($employee_id, $type = '', $deviceId = '')
    {
        $employee = $this->model->with(['doors2' => function ($q) {
            $q->with(["doorDevice"]);
        }])->findOrFail($employee_id);
        $deviceIp = Device::findOrFail($deviceId);

        if ($type === "new") {
            $this->user_management->addFPSecond($employee, $deviceIp->ip, \request()->input("finger"));
        } else {
            $this->user_management->copyFP($employee, \request()->input("device"), \request()->input("finger"));
        }

//        AddFinger::dispatch($employee, $deviceIp, $type, request()->input("finger"), request()->input("device"));

        return back();
    }
}
