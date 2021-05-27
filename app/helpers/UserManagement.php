<?php

namespace App\helpers;

use App\Models\Device;
use App\Models\Employee;
use App\Models\EmployeeDoor;
use App\Models\EmployeeFinger;
use Exception;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UserManagement
{
    private $device_ip = [];

    /**
     * Get device working status.
     *
     * @param $device
     * @return int
     */
    private function getDeviceStatus($device)
    {
        try {
            $status = Http::timeout(3)->withDigestAuth("admin", "M12345678t")
                ->get("http://{$device}/ISAPI/AccessControl/UserInfo/capabilities?format=json")
                ->status();
        } catch (ConnectException $exception) {
            $status = $exception->getCode();
        }

        return $status;
    }

    /**
     * @param Employee $employee
     * @return array|int|mixed
     */
    public function addUser(Employee $employee)
    {
        $employeeDoors = EmployeeDoor::with(['doorDevice' => function ($q) {
            $q->with('device');
        }])->where('employee_id', $employee->id)->get();
        $response = 0;
        $device_ip = [];
        foreach ($employeeDoors as $employeeDoor) {
            if ($employeeDoor->employee_device_status == 0 && $employeeDoor->doorDevice->device->checkDeviceStatus(true)) {
                $ip = $employeeDoor->doorDevice->device->ip;
                $device_ip[] = $employeeDoor->doorDevice->device->ip;
                $data = [
                    "UserInfo" => [
                        "employeeNo" => strval($employee->id),
                        "name" => "{$employee->first_name} {$employee->last_name}",
                        "userType" => "normal",
                        "Valid" => [
                            "enable" => true,
                            "beginTime" => "2020-08-01T17:30:08",
                            "endTime" => "2030-08-01T17:30:08",
                            "timeType" => "local",
                        ],
                        "password" => "",
                        "doorRight" => "1",
                        "RightPlan" => [
                            [
                                "doorNo" => 1,
                                "planTemplateNo" => "1"
                            ]
                        ],
                        "maxOpenDoorTime" => 0,
                        "openDoorTime" => 0,
                        "localUIRight" => false,
                        "userVerifyMode" => "cardOrFpOrPw"
                    ]
                ];
                $response = Http::withDigestAuth("admin", "M12345678t")
                    ->post("http://{$ip}/ISAPI/AccessControl/UserInfo/Record?format=json", $data)
                    ->json();
                if ($response["subStatusCode"] === "ok") {
                    $employeeDoor->update([
                        'employee_device_status' => 1
                    ]);
                }
            } else {

            }
        }
        $this->setIP($device_ip);
        return $response;
    }

    public function bindPhoto($employee, $photo)
    {
        $employeeDoors = EmployeeDoor::with(['doorDevice' => function ($q) {
            $q->with('device');
        }])->where('employee_id', $employee->id)->get();

        $response = 0;
        $device_ip = [];
        foreach ($employeeDoors as $employeeDoor) {
            $ip = $employeeDoor->doorDevice->device->ip;
            $device_ip[] = $employeeDoor->doorDevice->device->ip;

            $data = [
                "faceUrl" => $photo,
                "faceLibType" => "blackFD",
                "FDID" => "1",
                "FPID" => strval($employee->id),
                "name" => $employee->username(),
//            "gender" => "male"
            ];
            $response = Http::withDigestAuth("admin", "M12345678t")
                ->post("http://{$ip}/ISAPI/Intelligent/FDLib/FaceDataRecord?format=json", $data)
                ->json();

            if ($response["statusCode"] === 4) {
                $response = null;
            }

            $this->setIP($device_ip);
        }

        return $response;
    }

    public function edit($employee)
    {
        $response = '';
        $employeeDoors = EmployeeDoor::with(['doorDevice' => function ($q) {
            $q->with('device');
        }])->where('employee_id', $employee["id"])->get();

        foreach ($employeeDoors as $employeeDoor) {
            if (!$employeeDoor->doorDevice->device->checkDeviceStatus(true)) {
                continue;
            }

            $ip = $employeeDoor->doorDevice->device->ip;
            $device_ip[] = $employeeDoor->doorDevice->device->ip;
            $status = $this->getDeviceStatus($ip);
            if ($status === 200) {
                $data = [
                    "UserInfo" => [
                        "employeeNo" => strval($employee["id"]),
                        "name" => $employee["last_name"] . " " . ucfirst($employee["first_name"][0]) . ". " . ucfirst($employee["middle_name"][0]) . ".",
                        "userType" => "normal",
                        "Valid" => [
                            "enable" => true,
                            "beginTime" => now(),
                            "endTime" => "2030-08-01T17:30:08",
                            "timeType" => "local",
                        ],
                        "doorRight" => "1",
                        "RightPlan" => [
                            [
                                "doorNo" => 1,
                                "planTemplateNo" => "1"
                            ]
                        ],
                        "maxOpenDoorTime" => 0,
                        "openDoorTime" => 0,
                        "localUIRight" => false,
                        "userVerifyMode" => "cardOrFpOrPw"
                    ]
                ];

                $response = Http::withDigestAuth("admin", "M12345678t")
                    ->put("http://{$ip}/ISAPI/AccessControl/UserInfo/Modify?format=json", $data)
                    ->json();
            } else {
                continue;
            }
        }

        return $response;
    }

    /**
     * @param $employee
     * @return object|null
     */
    public function manualAddUser($employee)
    {
        $employeeDoors = EmployeeDoor::where('employee_id', $employee->id)->get();
        $response = NULL;

        foreach ($employeeDoors as $item) {
            if (!$item->doorDevice->device->checkDeviceStatus(true) || !isset($item->doorDevice->device->ip)) {
                continue;
            }
            $status = $this->personExistsOnDevice($employee, $item->doorDevice->device->ip);
            if (!$status) {
                $data = [
                    "UserInfo" => [
                        "employeeNo" => strval($employee->id),
                        "name" => $employee->username(),
                        "userType" => "normal",
                        "Valid" => [
                            "enable" => true,
                            "beginTime" => "2020-08-01T17:30:08",
                            "endTime" => "2030-08-01T17:30:08",
                            "timeType" => "local",
                        ],
                        "password" => "",
                        //"belongGroup" => "New Organization",
                        "doorRight" => "1",
                        "RightPlan" => [
                            [
                                "doorNo" => 1,
                                "planTemplateNo" => "1"
                            ]
                        ],
                        "maxOpenDoorTime" => 0,
                        "openDoorTime" => 0,
                        "localUIRight" => false,
                        "userVerifyMode" => "cardOrFpOrPw"
                    ]
                ];
                $response = Http::withDigestAuth($item->doorDevice->device->login ?? 'admin', $item->doorDevice->device->password ?? "M12345678t")
                    ->post("http://{$item->doorDevice->device->ip}/ISAPI/AccessControl/UserInfo/Record?format=json", $data)
                    ->object();
            }
            if (isset($response->subStatusCode) && $response->subStatusCode === 'deviceUserAlreadyExist') {
                $item->update(['employee_device_status' => 1]);
                session()->flash("successMessage", "Добавлено Успешно");
                continue;
            } elseif (isset($response->subStatusCode) && $response->subStatusCode === 'ok') {
                $item->update(['employee_device_status' => 1]);
                session()->flash("successMessage", "Добавлено Успешно");
                continue;
            } else {
                session()->flash("errorMessage", "Ошибка при добавлении сотрудника в устройство");
            }
            return $response;
        }
    }

    /**
     * @param array $device_ip
     */
    private function setIP($device_ip = [])
    {
        $this->device_ip = $device_ip;
    }

    /**
     * @return array
     */
    public function getIP(): array
    {
        return $this->device_ip;
    }

    /**
     * @param $employee
     * @param $device_ip
     * @return bool
     */
    public function personExistsOnDevice($employee, $device_ip)
    {
        $data = [
            "UserInfoSearchCond" => [
                "searchID" => '0',
                "searchResultPosition" => 0,
                "maxResults" => 10,
                "EmployeeNoList" => [
                    [
                        "employeeNo" => strval($employee->id)
                    ],
                ]
            ]
        ];
        $status = Http::withDigestAuth("admin", "M12345678t")
            ->post("http://{$device_ip}/ISAPI/AccessControl/UserInfo/Search?format=json", $data)
            ->json();

        if (isset($status->UserInfoSearch->responseStatusStrg)) {
            if ($status->UserInfoSearch->responseStatusStrg === 'OK' && $status->UserInfoSearch->numOfMatches === 1) {
                return true;
            } elseif ($status->UserInfoSearch->responseStatusStrg === 'NO MATCH') {
                return false;
            }
        }
    }

    /**
     * @param $employee
     * @param $device_ip
     * @return array|mixed
     */
    public function deletePerson($employee)
    {
        $employeeDoors = EmployeeDoor::with(['doorDevice' => function ($q) {
            $q->with('device');
        }])->where('employee_id', $employee->id)->get();
        $response = 0;
        foreach ($employeeDoors as $employeeDoor) {
            $ip = $employeeDoor->doorDevice->device->ip;
            $data = [
                "UserInfoDetail" => [
                    "mode" => "byEmployeeNo",
                    "EmployeeNoList" => [
                        [
                            "employeeNo" => strval($employee->id)
                        ]
                    ]
                ]
            ];

            if ($employeeDoor->doorDevice->device->checkDeviceStatus(true)) {
                $response = Http::withDigestAuth($employeeDoor->doorDevice->device->login, $employeeDoor->doorDevice->device->password)
                    ->timeout(5)
                    ->put("http://{$ip}/ISAPI/AccessControl/UserInfoDetail/Delete?format=json", $data)
                    ->json();

                if ($response["subStatusCode"] === "ok") {
                    $employeeDoor->update([
                        'employee_device_status' => 0
                    ]);
                }
            }
        }
        return $response;
    }

    public function updateUser($employee)
    {
        $employeeDoors = EmployeeDoor::with(['doorDevice' => function ($q) {
            $q->with('device');
        }])->where('employee_id', $employee->id)->get();

        $response = 0;
        foreach ($employeeDoors as $employeeDoor) {
            $ip = $employeeDoor->doorDevice->device->ip;
            $data = [
                "UserInfo" => [
                    "employeeNo" => strval($employee->id),
                    "name" => "{$employee->first_name} {$employee->last_name}",
                    "userType" => "normal",
                    "Valid" => [
                        "enable" => true,
                        "beginTime" => now()->format("Y-m-d\TH:i:s"),
                        "endTime" => "2030-08-01T17:30:08",
                        "timeType" => "local",
                    ],
                    "doorRight" => "1",
                    "RightPlan" => [
                        [
                            "doorNo" => 1,
                            "planTemplateNo" => "1"
                        ]
                    ],
                    "maxOpenDoorTime" => 0,
                    "openDoorTime" => 0,
                    "localUIRight" => false,
                    "userVerifyMode" => "cardOrFpOrPw"
                ]
            ];

            if ($employeeDoor->doorDevice->device->checkDeviceStatus(true)) {
                $response = Http::withDigestAuth("{$employeeDoor->doorDevice->device->login}", "{$employeeDoor->doorDevice->device->password}")
                    ->put("http://{$ip}/ISAPI/AccessControl/UserInfo/Modify?format=json", $data)
                    ->json();
            }
        }
        return $response;
    }

    /**
     * @param $employee
     * @param array $device_ip
     * @return array|mixed|string
     */
    public
    function checkFP($employee, $device_ip = [])
    {
        $response = "";
        $data = [
            "FingerPrintCond" => [
                "searchID" => "0",
                "employeeNo" => strval($employee->id),
            ]
        ];

        foreach ($device_ip as $device) {
            $response = Http::withDigestAuth("admin", "M12345678t")
                ->post("http://{$device->ip}/ISAPI/AccessControl/FingerPrintUpload?format=json", $data)
                ->json();
        }

        return $response;
    }

    /**
     * @param $employee
     * @return array|mixed|string
     */
    public
    function checkFPSecond($employee)
    {
        $response = "";
        $data = [
            "FingerPrintCond" => [
                "searchID" => "0",
                "employeeNo" => strval($employee->id),
            ]
        ];

        foreach ($employee->doors2 as $device) {
            if (isset($device->doorDevice->device->ip) && $device->doorDevice->device->checkDeviceStatus(true)) {
                $response = Http::withDigestAuth("admin", "M12345678t")
                    ->post("http://{$device->doorDevice->device->ip}/ISAPI/AccessControl/FingerPrintUpload?format=json", $data)
                    ->json();
            }
        }

        return $response;
    }

    /**
     * Get encoded fingerprint result.
     *
     * @param array $device_ip
     * @param bool $chosenDeviceIP
     * @param $fingerNo
     * @return Response
     * @throws Exception
     */
    private function getEncodedFP($device_ip = [], $chosenDeviceIP = false, $fingerNo = null)
    {
        $ip = $chosenDeviceIP ?? $device_ip[0]->ip;
        $deviceStatus = Device::where("ip", $ip)->first()->checkDeviceStatus(true);

        if ($deviceStatus) {
            $body = '<CaptureFingerPrintCond version="2.0" xmlns="http://www.isapi.org/ver20/XMLSchema">
                            <fingerNo>' . $fingerNo . '</fingerNo>
                            </CaptureFingerPrintCond>';

            return Http::withDigestAuth("admin", "M12345678t")
                ->withHeaders([
                    "Content-Type" => "text/xml;charset=utf-8"
                ])->send("POST", "http://{$ip}/ISAPI/AccessControl/CaptureFingerPrint", [
                    "body" => $body
                ]);
        }
    }

    /**
     * Bind encoded fingerprint result to employee.
     *
     * @param $employee
     * @param array $device_ip
     * @param bool $chosen_device_ip
     * @return mixed
     * @throws Exception
     */
    public
    function addFP($employee, $device_ip = [], $chosen_device_ip = false)
    {
        $encoded_fp = $this->getEncodedFP($device_ip, $chosen_device_ip);
        $xml = simplexml_load_string($encoded_fp);
        $json = json_encode($xml);
        $decoded_json = json_decode($json, true);

        if (isset($decoded_json)) {
            foreach ($device_ip as $k => $device) {
                $data = [
                    "FingerPrintCfg" => [
                        'employeeNo' => strval($employee->id),
                        "enableCardReader" => [1],
                        "fingerPrintID" => intval($decoded_json["fingerNo"]),
                        "fingerType" => "normalFP",
                        "fingerData" => $decoded_json["fingerData"],
                    ]
                ];
                if ($k === 0) {
                    $employee->update(['finger_data' => json_encode($data, JSON_UNESCAPED_UNICODE)]);
                }
                $response = Http::withDigestAuth("admin", "M12345678t")
                    ->post("http://{$device->ip}/ISAPI/AccessControl/FingerPrintDownload?format=json", $data);
            }

            return $response;
        }

        return 0;
    }


    /**
     * @param $employee
     * @param $device_ip
     * @param $fingerNo
     * @return Response|int
     */
    public function addFPSecond($employee, $device_ip, $fingerNo)
    {
        DB::transaction(function () use ($employee, $device_ip, $fingerNo) {
            $encoded_fp = $this->getEncodedFP([], $device_ip, $fingerNo);
            $xml = simplexml_load_string($encoded_fp);
            $json = json_encode($xml);
            $decoded_json = json_decode($json, true);

//            $employee->update([
//                'finger_data' => json_encode($decoded_json["fingerData"], JSON_UNESCAPED_UNICODE),
//                'fp' => 1
//            ]);

            if (array_key_exists("subStatusCode", $decoded_json)) {
                session()->flash("fingerError", "Ошибка при сканировании отпечаток пальца. Попробовать ещё раз?");
                session()->flash("employee_id", $employee->id);
                session()->flash("device_id", Device::where("ip", $device_ip)->first()->id);
                return 0;
            }

            if (isset($decoded_json["fingerData"])) {
                EmployeeFinger::create([
                    'name' => $fingerNo,
                    'data' => json_encode($decoded_json["fingerData"], JSON_UNESCAPED_UNICODE),
                    'employee_id' => $employee->id
                ]);

                foreach ($employee->doors2 as $k => $employeeDoor) {
                    if ($employeeDoor->employee_device_status !== 0 && $employeeDoor->doorDevice->device->checkDeviceStatus(true)) {
                        $data = [
                            "FingerPrintCfg" => [
                                "employeeNo" => strval($employee->id),
                                "enableCardReader" => [1],
                                "fingerPrintID" => intval($decoded_json["fingerNo"]),
                                "fingerType" => "normalFP",
                                "fingerData" => $decoded_json["fingerData"],
//                                "deleteFingerPrint" => true
                            ]
                        ];
                        $response = Http::withDigestAuth($employeeDoor->doorDevice->device->login, $employeeDoor->doorDevice->device->password)
                            ->post("http://{$employeeDoor->doorDevice->device->ip}/ISAPI/AccessControl/FingerPrintDownload?format=json", $data);
                        if ($response->json()["statusString"] === "OK") {
                            $employeeDoor->employee_finger_status = 1;
                            $employeeDoor->save();
                            session()->flash('successMessage', 'Успешно добавлен в устройство: ' . $employeeDoor->doorDevice->device->name ?? '');
                        } else {
                            session()->flash('errorMessage', 'Ошибка добавлении в устройство: ' . $employeeDoor->doorDevice->device->name ?? '');
                        }
                    }
                }
            }
        });

        return 0;
    }

    public
    function addFPThird($employee, $finger_data)
    {
        DB::transaction(function () use ($employee, $finger_data) {
            foreach ($employee->doors2 as $k => $employeeDoor) {
                if (!isset($employeeDoor->doorDevice->device->ip) || !$employeeDoor->doorDevice->device->checkDeviceStatus(true)) continue;
                $data = [
                    "FingerPrintCfg" => [
                        'employeeNo' => strval($employee->id),
                        "enableCardReader" => [1],
                        "fingerPrintID" => 1,
                        "fingerType" => "normalFP",
                        "fingerData" => $finger_data,
                    ]
                ];

//                $fp = $this->checkFP($employee, $employeeDoor->doorDevice->device->ip);

                $response = Http::withDigestAuth($employeeDoor->doorDevice->device->login ?? "admin", $employeeDoor->doorDevice->device->password ?? "M12345678t")
                    ->post("http://{$employeeDoor->doorDevice->device->ip}/ISAPI/AccessControl/FingerPrintDownload?format=json", $data);

                if ($response->json()["statusString"] === "OK") {
                    $employeeDoor->employee_finger_status = 1;
                    $employeeDoor->save();
                    session()->flash('successMessage', 'Успешно добавлен в устройство: ' . $employeeDoor->doorDevice->device->name ?? '');
                } else {
                    session()->flash('errorMessage', 'Ошибка добавлении в устройство: ' . $employeeDoor->doorDevice->device->name ?? '');
                }
            }
        });
    }

    public function copyFP($employee, $device_id, $fingerNo)
    {
        if (is_null($fingerNo)) {
            session()->flash("error", "Отпечаток не выбрано!!!");
        } else if (is_null($device_id)) {
            session()->flash("error", "Устройство не выбрано!!!");
        } else {
            $finger = EmployeeFinger::findOrFail($fingerNo);
            $device = Device::findOrFail($device_id);
            $employeeDoor = EmployeeDoor::with(['doorDevice' => function ($q) use ($device_id) {
                $q->with("device")->where("device_id", $device_id);
            }])
                ->where("employee_id", $employee->id)
                ->where("employee_finger_status", "<>", 1)
                ->get();

            $data = [
                "FingerPrintCfg" => [
                    'employeeNo' => strval($employee->id),
                    "enableCardReader" => [1],
                    "fingerPrintID" => intval($finger->name),
                    "fingerType" => "normalFP",
                    "fingerData" => $finger->data,
                ]
            ];

            if ($employeeDoor[0]->employee_device_status === 1) {
                $response = Http::withDigestAuth($device->login, $device->password)
                    ->post("http://{$device->ip}/ISAPI/AccessControl/FingerPrintDownload?format=json", $data);
                if ($response->json()["statusString"] === "OK") {
                    $employeeDoor[0]->employee_finger_status = 1;
                    $employeeDoor[0]->save();
                    session()->flash('success', 'Успешно копирован отпечаток на устройство: ' . $device->name);
                } else {
                    session()->flash('error', 'Ошибка при копировании на устройство: ' . $device->name);
                }
            } else {
                $log = new Log();
                $task = [
                    "key" => "employee_door",
                    "action" => "insert",
                    "value" => $employee->id
                ];
                $description = "Ошибка при добавлении отпечатка. Сотрудник не привязано на устройство!!!";
                session()->flash("error", "1 Новое сообщение!!!");
                $log->create($task, 0, $description);
            }
        }

        return 0;
    }

    public
    function addUserCron($employee, $device)
    {
        $data = [
            "UserInfo" => [
                "employeeNo" => strval($employee->id),
                "name" => $employee->username(),
                "userType" => "normal",
                "Valid" => [
                    "enable" => true,
                    "beginTime" => now(),
                    "endTime" => "2030-08-01T17:30:08",
                    "timeType" => "local",
                ],
                "password" => "",
                "doorRight" => "1",
                "RightPlan" => [
                    [
                        "doorNo" => 1,
                        "planTemplateNo" => "1"
                    ]
                ],
                "maxOpenDoorTime" => 0,
                "openDoorTime" => 0,
                "localUIRight" => false,
                "userVerifyMode" => "cardOrFpOrPw"
            ]
        ];
        return Http::withDigestAuth("{$device->login}", "{$device->password}")
            ->post("http://{$device->ip}/ISAPI/AccessControl/UserInfo/Record?format=json", $data)
            ->json();
    }

    public
    function updateUserCron($employee, $device)
    {
        $data = [
            "UserInfo" => [
                "employeeNo" => strval($employee["id"]),
                "name" => $employee["last_name"] . " " . ucfirst($employee["first_name"][0]) . ". " . ucfirst($employee["middle_name"][0]) . ".",
                "userType" => "normal",
                "Valid" => [
                    "enable" => true,
                    "beginTime" => now(),
                    "endTime" => "2030-08-01T17:30:08",
                    "timeType" => "local",
                ],
                "doorRight" => "1",
                "RightPlan" => [
                    [
                        "doorNo" => 1,
                        "planTemplateNo" => "1"
                    ]
                ],
                "maxOpenDoorTime" => 0,
                "openDoorTime" => 0,
                "localUIRight" => false,
                "userVerifyMode" => "cardOrFpOrPw"
            ]
        ];

        return Http::withDigestAuth("{$device->login}", "{$device->password}")
            ->put("http://{$device->ip}/ISAPI/AccessControl/UserInfo/Modify?format=json", $data)
            ->json();
    }

    public
    function deleteUserCron($employee, $device)
    {
        $data = [
            "UserInfoDetail" => [
                "mode" => "byEmployeeNo",
                "EmployeeNoList" => [
                    [
                        "employeeNo" => strval($employee->id)
                    ]
                ]
            ]
        ];
        return Http::withDigestAuth("{$device->login}", "{$device->password}")
            ->put("http://{$device->ip}/ISAPI/AccessControl/UserInfoDetail/Delete?format=json", $data)
            ->json();
    }

    public
    function addFingerCron($employee, $device)
    {
        $employeeFingers = EmployeeFinger::where("employee_id", $employee->id)->get();
        foreach ($employeeFingers as $employeeFinger) {
            $data = [
                "FingerPrintCfg" => [
                    'employeeNo' => strval($employee->id),
                    "enableCardReader" => [1],
                    "fingerPrintID" => intval($employeeFinger->name),
                    "fingerType" => "normalFP",
                    "fingerData" => "{$employeeFinger->data}",
                ]
            ];

            $response = Http::withDigestAuth("{$device->login}", "{$device->password}")
                ->post("http://{$device->ip}/ISAPI/AccessControl/FingerPrintDownload?format=json", $data);
        }
    }
}
