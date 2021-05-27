<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EmployeeDoor extends Pivot
{
    protected $fillable = [
        "employee_id", "doors_id", "door_device_id",
        "employee_device_status", "employee_finger_status", "added_at"
    ];

    //protected $with = ['doorDevice'];

    public function door()
    {
        return $this->belongsTo('App\Models\Door', 'doors_id')->withDefault();
    }

    public function doorDevice()
    {
        return $this->belongsTo('App\Models\DoorDevice', 'door_device_id')->withDefault();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id')->withDefault();
    }
}
