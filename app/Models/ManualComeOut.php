<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManualComeOut extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "manual_come_outs";
    protected $fillable = ["employees_id", "users_id", "come_at", "left_at", "reason", "description"];

    public function employees()
    {
        return $this->hasMany(Employee::class, "id", "employees_id");
    }

    public function users()
    {
        return $this->hasMany(User::class, "users_id", "id");
    }
}
