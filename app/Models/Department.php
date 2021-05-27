<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ["name", "company_id"];
    protected $table = "departments";

    public function companies()
    {
        return $this->belongsTo(Company::class, "company_id");
    }

    public function employees()
    {
        return $this->hasMany('App\Models\Employee');
    }

    public function positions()
    {
        return $this->hasMany('App\Models\Position');
    }


}
