<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComeOut extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "come_outs";

    protected $guarded = [];

//    protected $table = "clone_come_outs";

    public function employees()
    {
        return $this->belongsTo(Employee::class, "employee_id");
    }

    public function doorDevice()
    {
        return $this->belongsTo(DoorDevice::class, "doors_has_device_id")->withDefault();
    }

    ## SCOPES ##
//    public function scopeNowIn($query)
//    {
//        return $query->with("doorDevice")->whereHas("doorDevice", function ($query) {
//            return $query->where("is_come", 1);
//        })
//            ->get();
//    }

    public function scopeCountInOuts($query)
    {
        return $query->whereDate("action_time", now()->format("Y-m-d"))->count();
    }
}
