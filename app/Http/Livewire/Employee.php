<?php

namespace App\Http\Livewire;

use App\helpers\Log;
use App\helpers\UserManagement;
use App\Jobs\Device\AddUser;
use App\Jobs\Device\DeleteUser;
use App\Jobs\Device\UpdateUser;
use App\Models\Company;
use App\Models\CronTask;
use App\Models\DoorDevice;
use App\Models\EmployeeDoor;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Employee extends Component
{
    public \App\Models\Employee $model;
    public EmployeeDoor $employeeDoor;
    public array $parent = [];
    public array $child = [];
    public array $userVerifyModes = [];
    protected $listeners = ["destroy", "addFinger"];
    public string $deviceId = '';
    public array $deviceInfo = [];

    ## FILTER VARIABLES ##
    public string $filterName = '', $filterOrganization = '';
    public string $deletedEmployeeName = '', $deletedEmployeeOrganization = '';
    ## ## ## ###

    public $chooseDeviceForFinger = null;
    public $employeeDoors = [];
    public $schedule = null;

    public bool $isActive = true;
    public bool $isAsc = false;

    public $employeeId;

    public $page = 1;
    public $deletedPage = 1;

    public string $itn = "";

    protected $queryString = [
        "itn" => ["except" => ""],
        "filterName" => ["except" => ""],
        "filterOrganization" => ["except" => ""],
        "page" => ["except" => 1],
        "deletedEmployeeName" => ["except" => ""],
        "deletedPage" => ["except" => 1]
    ];

    public function mount(\App\Models\Employee $model, EmployeeDoor $employeeDoor)
    {
        $this->model = $model;
        $this->employeeDoor = $employeeDoor;
    }

    public function updatedItn($value)
    {
        $this->itn = $value;
    }

    ## Resetting Pagination After Filtering Data ##
//    public function updatingItn($value)
//    {
//        $this->resetPage();
//    }

    ## ## ## ## ## ## ## ## ## ## ## ## ## ## ##

    public function render()
    {
        $this->dispatchBrowserEvent("select2");

//        $modes = [
//            "cardAndPw",
//            "card", "cardOrPw",
//            "pf",
//            "fpAndPw",
//            "fpOrCard",
//            "fpAndCard",
//            "fpAndCardAndPw",
//            "faceOrFpOrCardOrPw",
//            "faceAndFp",
//            "faceAndPw",
//            "faceAndCard",
//            "face",
//            "employeeNoAndPw",
//            "fpOrPw",
//            "employeeNoAndFp",
//            "employeeNoAndFpAndPw",
//            "faceAndFpAndCard",
//            "faceAndPwAndFp",
//            "employeeNoAndFace",
//            "faceOrfaceAndCard",
//            "fpOrface",
//            "cardOrfaceOrPw",
//            "cardOrFace",
//            "cardOrFaceOrFp",
//            "cardOrFpOrPw",
//        ];
//
//        $this->userVerifyModes[] = $modes;

        if (!$this->isAsc) {
            $employees = $this->model->with("doors")->orderBy("id", "desc");
        } else {
            $employees = $this->model->with("doors")->orderBy("id");
        }
        $companies = Company::orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();

        ## ITN filter ##
        if (!empty($this->itn)) {
            $employees = $employees->where("description", "LIKE", $this->itn . "%");
        }
        ## ## ## ## ## ##

        if (!empty($this->filterName)) {
            $filterName = $this->filterName . '%';
            $employees = $employees->where('first_name', 'LIKE', $filterName)
                ->orWhere('last_name', 'LIKE', $filterName)
                ->orWhere('middle_name', 'LIKE', $filterName);
        }

        if (!empty($this->filterOrganization)) {
            $companies = Company::where("name", "like", "%" . $this->filterOrganization . "%")->get();

            foreach ($companies as $k => $company) {
                $employees = $employees->where("company_id", "=", $company->id);
            }
        }

        $employees = $employees->paginate(10)
            ->appends([
                "itn" => $this->itn,
                "filterName" => $this->filterName,
                "filterOrganization" => $this->filterOrganization
            ]);

        $employees->withPath("/admin/employees");

        $doorModel = DB::table("door_device")
            ->select("door_device.*", DB::raw("group_concat(doors_id) as doors"))
            ->groupBy("id", "doors_id", "device_id")
            ->get();

        $doors = [];
        foreach ($doorModel->unique("doors_id")->values()->all() as $door) {
            $doors[] = \App\Models\Door::where("id", $door->doors_id)->first();
        }

        $devices = \App\Models\Device::where(['status' => 1, 'active' => 1])
            ->orderBy('name')
            ->get();

        $firedEmployees = \App\Models\Employee::onlyTrashed()
            ->paginate(2)
            ->appends(["deletedEmployeeName" => $this->deletedEmployeeName]);

        if (!empty($this->deletedEmployeeName)) {
            $firedEmployees = $firedEmployees->where("first_name", "LIKE", $this->deletedEmployeeName . "%");

            $this->isActive = false;
        }

        $firedEmployees->withPath("/admin/employees");

        $errorTasks = CronTask::where("status", 3)->get();
        return view('livewire.employee', [
            "employees" => $employees,
            "firedEmployees" => $firedEmployees,
            "companies" => $companies,
            "branches" => $branches,
            "doors" => $doors,
            "devices" => $devices,
            "userVerifyModes" => $this->userVerifyModes,
            "departments" => \App\Models\Department::all(),
            "positions" => \App\Models\Position::all(),
            "schedules" => \App\Models\Schedule::with("employee")->get(),
            "errorTasks" => $errorTasks
        ]);
    }

    public function clear()
    {
        $this->reset("child");
        $this->reset("employeeDoors");
    }

    public function edit($employee_id, $door_id = null)
    {
        $employee = $this->model->findOrFail($employee_id);

        if ($employee) {
            $this->child["id"] = $employee->id;
            $this->child["first_name"] = $employee->first_name;
            $this->child["last_name"] = $employee->last_name;
            $this->child["middle_name"] = $employee->middle_name;
            $this->child["phone"] = $employee->phone;
            $this->child["description"] = $employee->description;
            $this->child["company_id"] = $employee->company_id;
            $this->child["position_id"] = $employee->position_id;
            $this->child["department_id"] = $employee->department_id;
            $this->child["branch_id"] = $employee->branch_id;
            $this->child["telegram_id"] = $employee->telegram_id;
            $employee->doors2->map(function ($item, $key) {
                $this->child["door_id"] = $item->doors_id;
            });
        } else {
            return back()->with('message', 'Пользователь не найден');
        }
    }

    public function update()
    {
        $isUpdated = false;
        if (isset($this->child["id"])) {
            $employee = $this->model->findOrFail($this->child["id"]);
            $employee->first_name = $this->child["first_name"];
            $employee->last_name = $this->child["last_name"];
            $employee->middle_name = $this->child["middle_name"];
            $employee->phone = (!empty($this->child["phone"])) ? str_replace([" ", '(', ')', '-', '+'], '', $this->child["phone"]) : NULL;
            $employee->description = $this->child["description"];
            $employee->company_id = $this->child["company_id"];
            $employee->position_id = $this->child["position_id"];
            $employee->department_id = $this->child["department_id"];
            $employee->telegram_id = $this->child["telegram_id"];
            $employee->branch_id = $this->child["branch_id"];
            if ($employee->isDirty()) {
                $employee->save();
                UpdateUser::dispatch($employee);
	        }

            // edit person for device when device not selected in modal
//            $this->editPerson($this->child);

//            $response = $userManagement->edit($employee, $v->device->ip);

            if (array_key_exists("door_id", $this->child)) {
                if (is_array($this->child["door_id"])) {
                    foreach ($this->child["door_id"] as $key => $value) {
                        $doorDevice = DoorDevice::where("doors_id", $value)->get();

                        foreach ($doorDevice as $v) {
                            $updated = $this->employeeDoor->updateOrCreate([
                                "employee_id" => $this->child["id"],
                                "doors_id" => $value,
                                "door_device_id" => $v->id,
                            ], ["employee_device_status" => 0, "employee_finger_status" => 0, "added_at" => now()]);

                            if (!$updated->wasChanged()) {
                                AddUser::dispatch($employee);
                            }
                        }

                        // edit person for device when selected device in modal
//                            $this->editPerson($employee);
                    }
                }
            }
        }
        $this->dispatchBrowserEvent("closeModal");

        if ($isUpdated) {
            $event = "sweety:update";
            $type = "success";
            $title = "Информация обновлено успешно!!!";
            $text = "";

            $this->flashMessage($event, $type, $title, $text);
            return 0;
        }
    }


    public function flashMessage($event, $type, $title, $text)
    {
        $this->dispatchBrowserEvent($event, [
            "type" => $type,
            "title" => $title,
            "text" => $text
        ]);
    }

    public function sweetyDeleteConfirm($employee_id)
    {
        $this->dispatchBrowserEvent("sweety:confirm-delete", [
            "type" => "warning",
            "title" => "Вы уверены?",
            "text" => "",
            "id" => $employee_id
        ]);
    }

    public function destroy($employee_id)
    {
        try {
            DB::transaction(function () use ($employee_id) {
                $employee = $this->model->with('doors2')->find($employee_id);
                if ($employee) {
//                    $userManagement = new UserManagement();
//                    if ($employee->doors2->count()) {
//                        foreach ($employee->doors2 as $employeeDoor) {
//                            if (isset($employeeDoor->doorDevice->device->ip) && $employeeDoor->doorDevice->device->checkDeviceStatus(true)) {
//                                $deletedFromDeviceStatus = $userManagement->deletePerson($employee, $employeeDoor->doorDevice->device->ip);
//                                if (isset($deletedFromDeviceStatus['subStatusCode']) && $deletedFromDeviceStatus['subStatusCode'] === 'ok') {
//                                    continue;
//                                }
//                            }
//                        }
//                    }
                    $isDeleted = $employee->delete();

                    if ($isDeleted) {
                        DeleteUser::dispatch($employee);
                    }

                    $this->dispatchBrowserEvent("select2");
                } else {
                    $this->dispatchBrowserEvent("sweety:isDeleted", [
                        "type" => "warning",
                        "title" => "Ошибка при удалении сотрудника!!!",
                        "text" => ""
                    ]);
                }
            });
        } catch (\Exception $exception) {
            return back()->with('errorMessage', $exception->getMessage());
        }
    }

    public function showPermissions($id)
    {
        $this->employeeDoors = EmployeeDoor::with(['door', 'employee', 'doorDevice' => function ($q) {
            $q->with('device');
        }])->whereNotNull('door_device_id')->where('employee_id', $id)->get();
    }

    public function connectWithDevice(\App\Models\Employee $employee)
    {
        $userManagement = new UserManagement();
        $userManagement->manualAddUser($employee);
        $this->dispatchBrowserEvent('modal', ['name' => '#permissionModal', 'action' => 'hide']);
    }

    public function addFinger($employeeId, $deviceId)
    {
        $employee = $this->model->with('doors2')->find($employeeId);
        if ($employee) {
            $this->model = $employee;
            $this->deviceId = $deviceId;
            $device = \App\Models\Device::findOrFail($this->deviceId);
            array_push($this->deviceInfo, $device);
//            (new UserManagement())->addFPThird($employee, $employee->finger_data);
            $this->dispatchBrowserEvent('modal', ['name' => '#permissionModal', 'action' => 'hide']);
            $this->dispatchBrowserEvent('modal', ['name' => '#selectDevice', 'action' => 'show']);
//            if (!$employee->finger_data) {
//
//            } else {
//                $hasNofingerData = $employee->doors2->where('employee_finger_status', 0);
//                if ($hasNofingerData->count()) {
//                    (new UserManagement())->addFPThird($employee, $employee->finger_data);
//                    session()->flush();
//                    session()->flash('message', 'Отпечаток Связано Успешно');
//                }
//                $this->dispatchBrowserEvent('modal', ['name' => '#permissionModal', 'action' => 'hide']);
//            }

        }
    }

## SCHEDULE ##
    public function schedule($employeeId)
    {
        $employee = $this->model->findOrFail($employeeId);
        if ($employee) {
            $this->employeeId = $employee->id;
        }
    }

    public function scheduleSave()
    {
        if (isset($this->employeeId)) {
            $employee = $this->model->findOrFail($this->employeeId);

            $message = $employee->update([
                "schedule_id" => $this->schedule
            ]);

            $this->reset("schedule");

            if ($message) {
                $this->dispatchBrowserEvent("sweety:bind-schedule", [
                    "type" => "success",
                    "title" => "График успешно связано!!!",
                    "text" => ""
                ]);
            } else {
                $this->dispatchBrowserEvent("sweety:bind-schedule", [
                    "type" => "warning",
                    "title" => "Ошибка при связи графика!!!",
                    "text" => ""
                ]);
            }

            $this->dispatchBrowserEvent("closeModal");
        }
    }
}
