<?php

namespace App\helpers\Cron;

use App\helpers\UserManagement;
use App\Models\Employee;

class EmployeeFinger
{
    public function getEmployee($employee)
    {
        return Employee::with("doors2")->findOrFail($employee);
    }

    public function main($task, $device)
    {
        $userManagement = new UserManagement();
        $message = 'not done';
        switch (json_decode($task->task)->action) {
            case 'insert':
                $employee = $this->getEmployee(json_decode($task->task)->value);
                $status = $userManagement->addFingerCron($employee, $device);
        }

        return $message;
    }
}
