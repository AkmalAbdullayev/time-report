<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ["name", "department_id"];
    protected $table = "positions";

    public function departments()
    {
        return $this->belongsTo(Department::class, "department_id");
    }
}
