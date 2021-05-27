<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ["name", "company_id"];
    protected $table = "branches";

    public function companies()
    {
        return $this->belongsTo(Company::class, "company_id");
    }
}
