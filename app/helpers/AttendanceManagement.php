<?php

namespace App\helpers;

use App\Models\ComeOut;
use App\Models\Device;
use App\Models\DoorDevice;
use App\Models\EmployeeDoor;
use Exception;
use Illuminate\Support\Facades\Http;

class AttendanceManagement
{
    public function between($from, $to, $searchResultPosition = 0)
    {
        $data = [
            "AcsEventCond" => [
                "searchID" => "0",
                "searchResultPosition" => $searchResultPosition,
                "maxResults" => 10,
                "major" => 0,
                "minor" => 0,
                "eventAttribute" => "attendance",
                "startTime" => $from,
                "endTime" => $to,
            ]
        ];

        return Http::withDigestAuth("admin", "M12345678t")
            ->post("http://192.168.1.22/ISAPI/AccessControl/AcsEvent?format=json", $data)
            ->json();
    }

    public function comeOuts($device, $searchResultPosition = 0, $startTime = null, $to = null)
    {
        if (is_null($startTime)) {
            $startTime = now()->year . "-01-01";
        }
        if (is_null($to)) {
            $to = now()->addDay()->format("Y-m-d");
        }

        $data = [
            "AcsEventCond" => [
                "searchID" => "0",
                "searchResultPosition" => $searchResultPosition,
                "maxResults" => 10,
                "major" => 0,
                "minor" => 0,
                "eventAttribute" => "attendance",
                "timeReverseOrder" => true,
                "startTime" => $startTime,
                "endTime" => $to
            ]
        ];

        if ($this->checkDeviceStatus($device) === 200) {
            return Http::withDigestAuth("{$device->login}", "{$device->password}")
                ->post("http://{$device->ip}/ISAPI/AccessControl/AcsEvent?format=json", $data)
                ->json();
        }

        return false;
    }

    public function cloneComeOuts($device, $searchResultPosition = 0, $from = '2021-05-01')
    {
        $data = [
            "AcsEventCond" => [
                "searchID" => "0",
                "searchResultPosition" => $searchResultPosition,
                "maxResults" => 10,
                "major" => 0,
                "minor" => 0,
                "eventAttribute" => "attendance",
//                "timeReverseOrder" => true,
                "startTime" => $from,
                "endTime" => now()->addDay()->format("Y-m-d")
            ]
        ];

        return Http::withDigestAuth("{$device->login}", "{$device->password}")
            ->post("http://{$device->ip}/ISAPI/AccessControl/AcsEvent?format=json", $data)
            ->json();
    }

//    public function liveAttendance($company = null, $searchResultPosition = 0)
//    {
//        if (!is_null($company)) {
//            $data = [
//                "AcsEventCond" => [
//                    "searchID" => "0",
//                    "searchResultPosition" => $searchResultPosition,
//                    "maxResults" => 10,
//                    "major" => 0,
//                    "minor" => 0,
//                    "eventAttribute" => "attendance",
//                    "timeReverseOrder" => true
//                ]
//            ];
//
////            return Http::withDigestAuth("{$login}", "{$password}")
////                ->post("http://{$ip}/ISAPI/AccessControl/AcsEvent?format=json", $data)
////                ->json();
//        } else {
//
//        }
////            $devices = Device::all();
////            $response = [];
////
////            $data = [
////                "AcsEventCond" => [
////                    "searchID" => "0",
////                    "searchResultPosition" => $searchResultPosition,
////                    "maxResults" => 10,
////                    "major" => 0,
////                    "minor" => 0,
////                    "eventAttribute" => "attendance",
////                    "timeReverseOrder" => true
////                ]
////            ];
////
////            foreach ($devices as $k => $device) {
////                $status = $this->checkDeviceStatus($device);
////                $doorDevice = DoorDevice::where("device_id", $device->id)->first();
////
////                if ($status === 200) {
////                    $response = Http::withDigestAuth("{$device->login}", "{$device->password}")
////                        ->timeout(5)
////                        ->post("http://{$device->ip}/ISAPI/AccessControl/AcsEvent?format=json", $data)
////                        ->json();
////
////                    if (!empty($response)) {
////                        if (array_key_exists("AcsEvent", $response)) {
////                            foreach ($response["AcsEvent"]["InfoList"] as $list) {
////                                $employeeDoor = EmployeeDoor::where("employee_id", $list["employeeNoString"])->first();
////
////                                if (!is_null($employeeDoor)) {
////                                    $comeOut = new ComeOut();
////                                    $comeOut->doors_has_device_id = $doorDevice->id;
////                                    $comeOut->employee_id = $employeeDoor->id;
////                                    $comeOut->action_time = date("Y-m-d H:i:s", strtotime($list["time"]));
////                                    $comeOut->event_serial_no = $list["serialNo"];
////
////                                    $comeOut->save();
////                                }
////                            }
////                        }
////                    }
////                } else {
////                    continue;
////                }
////            }
////
////            return $response;
////        }
//    }

    public function checkDeviceStatus($device)
    {
        try {
            return Http::timeout(5)
                ->withDigestAuth($device->login, $device->password)
                ->get("http://{$device->ip}/ISAPI/AccessControl/UserInfo/capabilities?format=json")
                ->status();

        } catch (Exception $exception) {
            return $exception->getCode();
        }
    }
}
