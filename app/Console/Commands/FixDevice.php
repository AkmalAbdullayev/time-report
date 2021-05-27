<?php

namespace App\Console\Commands;

use App\helpers\Cron\EmployeeFinger;
use App\helpers\UserManagement;
use App\Models\CronTask;
use App\Models\Device;
use App\Models\Employee;
use App\Models\EmployeeDoor;
use Illuminate\Console\Command;

class FixDevice extends Command
{
    private $userManagement;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix bug occurred with device';

    /**
     * Create a new command instance.
     *
     * @param UserManagement $userManagement
     */
    public function __construct(UserManagement $userManagement)
    {
        parent::__construct();
        $this->userManagement = $userManagement;
    }

    public function getEmployeeDevice($employee)
    {
        $employee = EmployeeDoor::with(['door', 'employee', 'doorDevice' => function ($q) {
            $q->with('device');
        }])->whereNotNull('door_device_id')->where('employee_id', json_decode($employee)->value)->get();

        return $employee;
    }

    public function getEmployee($employee)
    {
        return Employee::with("doors2")->findOrFail($employee);
    }

    public function getEmployeeDeviceStatus($employee, $device)
    {
        $device_id = Device::where("ip", $device)->first();
//        print_r($device_id->id . PHP_EOL);
        return EmployeeDoor::with("doorDevice")->whereHas("doorDevice", function ($query) use ($device_id) {
            $query->where("device_id", $device_id->id);
        })->where("employee_id", $employee)->first();
    }

    public function deleteEmployeeAction($employee, $device)
    {
        $is_error = false;
        $employeeDeviceStatus = $this->getEmployeeDeviceStatus($employee->id, $device->ip);
        if ($employeeDeviceStatus->employee_device_status == 1) {
            $status = $this->userManagement->deleteUserCron($employee, $device);
            if ($status["subStatusCode"] == "ok") {
                $this->info("DELETED SUCCESSFULLY");
            }
        } else {
            $is_error = true;
        }

        return $is_error;
    }

    public function employeeDoorAction($task, $device)
    {
        $message = "not done";
        switch (json_decode($task->task)->action) {
            case "insert":
                // get employee model
                $employee = $this->getEmployee(json_decode($task->task)->value);
                // adding user to device
                $status = $this->userManagement->addUserCron($employee, $device);
                // get $employee->employee_device_status
                $employeeDeviceStatus = $this->getEmployeeDeviceStatus($employee->id, $device->ip);
                if ($status["subStatusCode"] === "ok") {
                    $this->info($employee->full_name . " добавлено успешно!!! ID = {$employee->id}");
                    $message = "done";
                    $employeeDeviceStatus->employee_device_status = 1;
                    $employeeDeviceStatus->save();
                }
                if ($status["subStatusCode"] === "deviceUserAlreadyExist") {
                    $message = "not done";
                    $this->error("USER EXISTS!!!");
                    $task->description = "USER ALREADY EXISTS IN DEVICE!!!";
                }
                break;
            case "update":
                $employee = $this->getEmployee(json_decode($task->task)->value);
                $employeeDeviceStatus = $this->getEmployeeDeviceStatus($employee->id, $device->ip);
                if ($employeeDeviceStatus->employee_device_status == 1) {
                    $status = $this->userManagement->updateUserCron($employee, $device);
                    if ($status["subStatusCode"] === "ok") {
                        $message = "done";
                        $this->info("Successfully updated!!!");
                    }
                } else {
                    $message = "not done";
                    $this->error("User Not binded to device");
                    $task->description = "USER NOT BINDED TO DEVICE!!!";
                }
                break;
            case 'delete':
                $employee = $this->getEmployee(json_decode($task->task)->value);
                $is_error = $this->deleteEmployeeAction($employee, $device);
                if (!$is_error) {
                    $message = 'done';
                }
        }

        return $message;
    }

    public function message($message, $task)
    {
        switch ($message) {
            case 'done':
                $task->status = 1;
                $task->description = "DONE";
                $task->save();
                break;
            case 'not done':
                $task->status = 2;
                $task->save();
                break;
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tasks = CronTask::where("status", 0)->get();
        if ($tasks->isEmpty()) {
            $this->info("There are no tasks to be done!!!");
            return 0;
        }

        foreach ($tasks as $task) {
            $employeeDevices = $this->getEmployeeDevice($task->task);
            foreach ($employeeDevices as $k => $employeeDevice) {
                $deviceStatus = $employeeDevice->doorDevice->device->checkDeviceStatus(true);

                if ($deviceStatus) {
                    print_r($employeeDevice->doorDevice->device->ip);
                    $this->info(" - АКТИВНО!!!");
                    switch (json_decode($task->task)->key) {
                        case "employee_door":
                            $message = $this->employeeDoorAction($task, $employeeDevice->doorDevice->device);
                            $this->message($message, $task);
                            break;
                        case "employee_finger":
                            $employeeFinger = new EmployeeFinger();
                            $message = $employeeFinger->main($task, $employeeDevice->doorDevice->device);
                            $this->message($message, $task);
                    }
                } else {
                    $this->info($employeeDevice->doorDevice->device->ip . " - Устройство не активно!!!");
                }
            }
        }
        return 0;
    }
}
