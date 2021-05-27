<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Door extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

    public function devices()
    {
        return $this->belongsToMany(Device::class, "door_device", "doors_id", "device_id")
            ->withPivot("doors_id");
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, "employee_door", "doors_id", "employee_id");
    }
}
