<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoorDevice extends Model
{
    protected $table = "door_device";
    protected $guarded = [];

    public function device()
    {
        return $this->belongsTo('App\Models\Device', 'device_id');
    }

    public function doors()
    {
        return $this->belongsTo(Door::class);
    }

    public function comeOuts()
    {
        return $this->hasMany(ComeOut::class);
    }

}
