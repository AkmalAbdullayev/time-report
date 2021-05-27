<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ComeOutController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DoorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AjaxController;
use App\Http\Livewire\DeviceManager;
use App\Models\EmployeeFinger;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

## FACE ##
Route::get("/face", function () {
    /*    $data = [
            "searchResultPosition" => 0,
            "maxResults" => 1,
            "faceLibType" => "blackFD",
            "FDID" => "1"
        ];

        dd(Http::withDigestAuth("admin", "M12345678t")
            ->post("http://192.168.11.204/ISAPI/Intelligent/FDLib/FDSearch?format=json", $data)
            ->json());*/

//    dd(Http::withDigestAuth("admin", "M12345678t")
//        ->get("http://192.168.11.204/ISAPI/AccessControl/CaptureFaceData/capabilities")
//        ->body());

    ## ADDING PERSON ##
    /*    $employee = \App\Models\Employee::find(38);
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

        return Http::withDigestAuth("admin", "M12345678t")
            ->post("http://192.168.0.112/ISAPI/AccessControl/UserInfo/Record?format=json", $data)
            ->json();*/
    ## ## ## ## ## ## #

    ## UPDATE PERSON ##
    /*$employee = Employee::find();
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

    return Http::withDigestAuth("admin", "M12345678t")
        ->put("http://192.168./ISAPI/AccessControl/UserInfo/Modify?format=json", $data)
        ->json();*/
    ## ## ## ## ## ## #

    ## DELETE PERSON ##
    /*$data = [
        "UserInfoDetail" => [
            "mode" => "byEmployeeNo",
            "EmployeeNoList" => [
                [
                    "employeeNo" => "38"
                ]
            ]
        ]
    ];

    return Http::withDigestAuth("admin", "M12345678t")
        ->put("http://192.168.0.112/ISAPI/AccessControl/UserInfoDetail/Delete?format=json", $data)
        ->json();*/
    ## ## ## ## ## ## #

    ## SEARCH FOR PERSON ##
    $data = [
        "UserInfoSearchCond" => [
            "searchID" => "0",
            "searchResultPosition" => 0,
            "maxResults" => 10,
            "EmployeeNoList" => [
                [
                    "employeeNo" => "38"
                ],
            ],
        ],
    ];
    return Http::withDigestAuth("admin", "M12345678t")
        ->post("http://192.168.12.201/ISAPI/AccessControl/UserInfo/Search?format=json", $data)
        ->json();
    ## ## ## ## ## ## ## ##

    ## COLLECT FACE DATA ##
    /*    $data = '<CaptureFaceDataCond>
                        <captureInfrared>true</captureInfrared>
                        <dataType>binary</dataType>
                    </CaptureFaceDataCond>';


        return Http::withDigestAuth("admin", "M12345678t")
            ->withHeaders([
                "Content-Type" => "text/xml;"
            ])->send("POST", "http://192.168.1.199/ISAPI/AccessControl/CaptureFaceData", [
                "body" => $data
            ]);*/
    ## ## ## ## ## #

    ## BIND PICTURE TO PERSON ##
    /*    $data = [
            "faceUrl" => "http://207.148.21.210/akmal.jpg",
            "faceLibType" => "blackFD",
            "FDID" => "1",
            "FPID" => "10",
            "name" => "Abdullayev Akmal",
            "gender" => "male"
        ];
        return Http::withDigestAuth("admin", "M12345678t")
            ->post("http://192.168.1.199/ISAPI/Intelligent/FDLib/FaceDataRecord?format=json", $data)
            ->json();*/
    ## ## ## ## ## ## ## ## ## #

    ## DELETE PHOTO ##
    /*    $data = [
            "FPID" => [
                [
                    "value" => "10"
                ]
            ]
        ];

        return Http::withDigestAuth("admin", "M12345678t")
            ->put("http://192.168.1.199/ISAPI/Intelligent/FDLib/FDSearch/Delete?format=json&FDID=1&faceLibType=blackFD", $data)
            ->json();*/
    ## ## ## ## ## ##

    ## GET FINGERPRINT DATA ##
    /*    $data = [
            "FingerPrintCond" => [
                "searchID" => "0",
                "employeeNo" => "38",
            ]
        ];
        return Http::withDigestAuth("admin", "M12345678t")
            ->post("http://192.168.0.112/ISAPI/AccessControl/FingerPrintUpload?format=json", $data)
            ->json();*/
    ## ## ## ## ## ## ## ## ##

    ## ADD FINGERPRINT DATA ##
    $employee = EmployeeFinger::where("employee_id", 38)->first();
    $data = [
        "FingerPrintCfg" => [
            "employeeNo" => strval($employee->employee_id),
            "enableCardReader" => [1],
            "fingerPrintID" => intval($employee->name),
            "fingerType" => "normalFP",
            "fingerData" => "{$employee->data}",
        ]
    ];

    return Http::withDigestAuth("admin", "M12345678t")
        ->post("http://192.168./ISAPI/AccessControl/FingerPrintDownload?format=json", $data)
        ->json();
    ## ## ## ## ## ## ## ## ##
});
## ## ## ## ## ## ## ##

Route::get("/", function () {
    return redirect()->route("home");
});

## DEVICE MANAGER ##
Route::middleware(["role:device-manager"])->group(function () {
    Route::get("/device", DeviceManager::class)->name("test");
});

Route::prefix("admin")->middleware(["auth:sanctum", "verified"])->group(function () {
    Route::get("/", [HomeController::class, "index"])->name("home");
    Route::resource("users", UserController::class);
    Route::resource("roles", RoleController::class);
    Route::resource("devices", DeviceController::class)->only(["index", "edit", "delete", "store"]);
    Route::resource("doors", DoorController::class)->only(["index", "edit", "delete", "store", "update"]);
    Route::resource("company", CompanyController::class);
    Route::resource("branches", BranchController::class);
    Route::resource("department", DepartmentController::class);
    Route::resource("position", PositionController::class);
    Route::resource("permission", PermissionController::class);
    Route::resource("client", ClientController::class);
    Route::get("weekend", [\App\Http\Controllers\WeekendController::class, 'index'])->name('weekend');
    Route::get("schedule", [ScheduleController::class, "index"])->name("schedule.index");
    Route::any("holiday", [\App\Http\Controllers\HolidayController::class, 'index'])->name('holiday');
    Route::any("weekend-exclude", [\App\Http\Controllers\WeekendExcludeController::class, 'index'])->name('weekend.exclude');
    Route::any("attendance-setting", [\App\Http\Controllers\AttendanceSettingController::class, 'index'])->name('attendance.setting');
    Route::any("door", [DoorController::class, 'door'])->name("door.crud");;

    Route::post("employees/fp/{employee}/{type}/{device?}", [EmployeeController::class, "addFingerPrint"])->name("add-fp");
//    Route::post("employees/employee/{employee}/{door}", [EmployeeController::class, "addEmployee"])->name("add-employee");
    Route::resource("employees", EmployeeController::class)->only(["index", "store", "update"]);

    ## REPORT ##
    Route::prefix("report")->group(function () {
        Route::get("full-report", [ReportController::class, "full"])->name("full-report");
        Route::get("personal-report", [ReportController::class, "personal"])->name("personal-report");
        Route::get("export-personal-report", [ReportController::class, "personal"])->name("exportPersonalReport");
        Route::get("export-personal-today-report", [ReportController::class, "personal"])->name("exportPersonalReportToday");
        Route::get("manual-report", [ReportController::class, 'manual'])->name("manual-report");
        Route::post("manual-report/store", [ReportController::class, 'storeManual'])->name("store-manual");
        Route::delete("manual-report/delete/{id}", [ReportController::class, "deleteManual"])->name("delete-manual");
        Route::get("temporary", [ReportController::class, "temporary"])->name("temporary-report");
        Route::get("export-temporary-report", [ReportController::class, "temporary"])->name("exportTemporaryReport");
        Route::get("personal2", [ReportController::class, "personal2"])->name("personal2");
        Route::get("presences", [ReportController::class, "presences"])->name("presences");
        Route::get("export-presences-report", [ReportController::class, "presences"])->name("exportPresencesReport");
        Route::get('come-out', [ReportController::class, "comeOut"])->name('come.out.report');
        Route::get('in-office', [ReportController::class, "inOffice"])->name('in.office.report');
        Route::get('{id}/come-out-edit', [ComeOutController::class, "edit"])->name('come.out.edit');
        Route::post('come-out-update', [ComeOutController::class, "update"])->name('come.out.update');
        Route::delete('come-out-delete', [ComeOutController::class, "delete"])->name('come.out.delete');
        Route::post('come-out-save', [ComeOutController::class, "store"])->name('come.out.store');
    });

    ## API ##
    Route::get("aj/positions/{department_id}", [AjaxController::class, "positions"]);
    Route::get("aj/branches/{company_id}", [AjaxController::class, "branches"]);
    Route::get("aj/departments/{company_id}", [AjaxController::class, "departments"]);

    ## COME-OUT ##
    Route::get("live", [ComeOutController::class, "index"])->name("come-out");
    Route::get("reduced", [ComeOutController::class, "reduced"])->name("reduced");
});

//Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//    return view('dashboard');
//})->name('dashboard');

//Auth::routes();

Route::get("device", function () {
    $data = [
        "UserRightWeekPlanCfg" => [
            "enable" => "true",
            "WeekPlanCfg" => [
                [
                    "week" => "Monday",
                    "id" => 1,
                    "enable" => "true",
                    "TimeSegment" => [
                        "beginTime" => "14:30:00",
                        "endTime" => "15:00:00"
                    ]
                ]
            ]
        ]
    ];
    return Http::withDigestAuth("admin", "M12345678t")
        ->put("http://192.168.0.118/ISAPI/AccessControl/UserRightWeekPlanCfg/1?format=json", $data)
        ->json();
});
