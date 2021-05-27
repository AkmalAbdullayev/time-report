<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function index()
    {
        // user add
        $data = [
            "UserInfo" => [
                "employeeNo" => "6",
                "name" => "John Doe",
                "userType" => "normal",
                "Valid" => [
                    "enable" => true,
                    "beginTime" => "2020-08-01T17:30:08",
                    "endTime" => "2030-08-01T17:30:08",
                    "timeType" => "local",
                ],
                "password" => "",
                "belongGroup" => "New Organization",
                "doorRight" => "1",
                "RightPlan" => [
                    [
                        "doorNo" => 1,
                        "planTemplateNo" => "3"
                    ]
                ],
                "maxOpenDoorTime" => 0,
                "openDoorTime" => 0,
                "localUIRight" => false,
            ]
        ];

        // fingerprint add
//        $data = [
//            "FingerPrintCfg" => [
//                'employeeNo' => "6",
//                "enableCardReader" => [1],
//                "fingerPrintID" => 5,
//                "fingerType" => "normalFP",
//                "fingerData" => "MzAxHzQpJoikCEllFliQfGqtFXhs6nstJUiE2nkNJriQeHLpFnigioQdJ1ikjqQtFWhs1qvpFciMBYV5FEicVqOFFCiAUrFtJYiEV8cpJqh0GN/JJEhUSNwZFWhYRN/FFfiJSeJpJmh0k+QBFsiGHbI1F1icH/whJ2iUIfkBJhhpMvwpFohcLPW1FEhkvvLxFUhpOD0FJTiQ3Ez9JEig1XopF1igFc1lFziAm9aFJphwK9VlFqholgzKFBhoRAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAle4zTBMAXFcRg7kIBRwFAyFzC1erWkFjxQR4DysQQVIMGCcTIKLuB9bMISF1iQLqYWoiQ1YD2g5UUDHKHZbXCBKg7Q9JnUsAUOARHbptBFV9BYoMIwFiog5Eu21gU34GjnVSBNGZFzhRahcCiApSqxVAdM4BfM8lUDCdDUiiAjdVJRnVTwlgMBEWvgAAAAAAGXs=",
//            ]
//        ];

//        $data = [
//            "FingerPrintCond" => [
//                "searchID" => "1",
//                "employeeNo" => "1"
//            ]
//        ];

//        $data = [
//            "UserInfoSearchCond" => [
//                "searchID" => "1",
//                "searchResultPosition" => 0,
//                "maxResults" => 30,
//                "EmployeeNoList" => [
//                    [
//                        "employeeNo" => "3"
//                    ]
//                ]
//            ]
//        ];

        $data = [
            "AcsEventCond" => [
                "searchID" => "0",
                "searchResultPosition" => 0,
                "maxResults" => 10,
                "major" => 1,
                "minor" => 7,
                "eventAttribute" => "attendance",
                "employeeNo" => "2",
            ]
        ];

        // AcsEventTotalNum
//        $data = [
//          "AcsEventTotalNumCond" => [
//              "major" => 0,
//              "minor" => 0,
//              "employeeNoString" => "2",
//              "eventAttribute" => "attendance"
//          ]
//        ];

        $data = [
            "AcsEventCond" => [
                "searchID" => "5",
                "searchResultPosition" => 70,
                "maxResults" => 10,
                "major" => 0,
                "minor" => 0,
                "employeeNoString" => "3",
                "eventAttribute" => "attendance"
            ]
        ];

        return Http::withDigestAuth("admin", "M12345678t")
            ->post("http://192.168.1.10/ISAPI/AccessControl/AcsEvent?format=json", $data)
//            ->post("http://192.168.1.10/ISAPI/AccessControl/AcsEventTotalNum?format=json", $data)
//            ->get("http://192.168.1.23/ISAPI/AccessControl/UserInfo/capabilities?format=json")
//            ->post("http://192.168.1.12/ISAPI/AccessControl/UserInfo/Search?format=json", $data)
//            ->post("http://192.168.1.12/ISAPI/AccessControl/UserInfo/Record?format=json", $data)
//            ->post("http://192.168.1.17/ISAPI/AccessControl/FingerPrintUpload?format=json", $data)
//            ->post("http://192.168.1.12/ISAPI/AccessControl/FingerPrintDownload?format=json", $data)
            ->json();

        // adding fingerprint

//        return Http::withDigestAuth("admin", "M12345678t")
//            ->withHeaders([
//            "Content-Type" => "text/xml;charset=utf-8"
//        ])->send("POST", "http://192.168.1.12/ISAPI/AccessControl/CaptureFingerPrint", [
//            "body" => '<CaptureFingerPrintCond version="2.0" xmlns="http://www.isapi.org/ver20/XMLSchema">
//<fingerNo>5</fingerNo>
//</CaptureFingerPrintCond>'
//        ]);
    }
}
