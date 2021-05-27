<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

    public function employee()
    {
        return $this->hasMany("App\Models\Employee");
    }

    public function branches()
    {
        return $this->hasMany(Branch::class, "company_id");
    }
}
