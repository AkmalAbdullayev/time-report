<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronTask extends Model
{
    use HasFactory;

    protected $table = "cron_tasks";
    protected $guarded = [];

    public function getEmployeeAttribute($value)
    {
        return Employee::findOrFail(json_decode($this->task)->value)->full_name;
    }
}
