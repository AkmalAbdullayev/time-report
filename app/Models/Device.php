<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ["name", "ip", "login", "password", "status", "active"];

    public function doors()
    {
        return $this->belongsToMany(Door::class, "door_device", "device_id", "doors_id");
    }

    public function checkDeviceStatus($isActive = false)
    {
        try {
            $status = Http::timeout(2)->withDigestAuth($this->login, $this->password)
                ->get("http://{$this->ip}/ISAPI/AccessControl/UserInfo/capabilities?format=json")
                ->status();

            if ($status === 200) {
                $message = '<span class="badge badge-success">На линии</span>';
                return $isActive ? true : $message;

            }
        } catch (Exception $exception) {
            $message = '<span class="badge badge-danger">Нет связи</span>';
            return $isActive ? false : $message;
        }
    }
}
