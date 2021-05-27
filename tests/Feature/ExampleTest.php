<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\NoReturn;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testBasicTest()
    {
        $data = [
            "UserInfoSearchCond" => [
                "searchID" => "0",
                "searchResultPosition" => 0,
                "maxResults" => 10,
                "EmployeeNoList" => [
                    [
                        "employeeNo" => "18"
                    ]
                ]
            ]
        ];

//        $data = [
//            "UserInfoDelCond" => [
//                "EmployeeNoList" => [
//                    [
//                        "employeeNo" => "201"
//                    ]
//                ]
//            ]
//        ];

//        $data = [
//            "FingerPrintCond" => [
//                "searchID" => "0",
//                "employeeNo" => "1",
//            ]
//        ];

        $data = [
            "AcsEventCond" => [
                "searchID" => "0",
                "searchResultPosition" => 0,
                "maxResults" => 10,
                "major" => 0,
                "minor" => 0,
                "eventAttribute" => "attendance",
                "startTime" => "2021-02-20",
                "endTime" => "2021-02-24"
            ]
        ];

//        $response = Http::withDigestAuth("admin", "M12345678t")
////            ->get("http://192.168.1.23/ISAPI/AccessControl/UserInfoDetail/Delete/capabilities?format=json")
////            ->put("http://83.221.162.44:8207/ISAPI/AccessControl/UserInfo/Delete?format=json", $data)
////            ->post("http://83.221.162.44:8206/ISAPI/AccessControl/UserInfo/Search?format=json", $data)
////            ->get("http://192.168.12.202/ISAPI/AccessControl/AcsEvent/capabilities?format=json")
////            ->get("http://192.168.12.203/ISAPI/AccessControl/UserInfo/capabilities?format=json")
////            ->post("http://192.168.12.202/ISAPI/AccessControl/UserInfo/Search?format=json", $data)
////            ->get("http://192.168.12.201/ISAPI/AccessControl/UserInfo/capabilities?format=json")
//            ->get("http://192.168.1.11/ISAPI/AccessControl/DoorStatusHolidayPlanCfg/capabilities?format=json")
////            ->get("http://192.168.1.10/ISAPI/AccessControl/FingerPrintProgress?format=json")
////            ->post("http://83.221.162.44:8207/ISAPI/AccessControl/FingerPrintUpload?format=json", $data)
////            ->post("http://192.168.1.10/ISAPI/AccessControl/UserInfo/Search?format=json", $data)
//                ->json();

        ## FACE ##

        ## Add Face Library ##
        $data = [
            "faceLibType" => "infraredFD",
            "name" => "test"
        ];
        ## ## ## ## ## ## ## #

        ## Collect Face Data ##
        $data = '<CaptureFaceDataCond version="2.0" xmlns="http://www.isapi.org/ver20/XMLSchema">
<captureInfrared>true<captureInfrared>
<dataType>url<dataType>
</CaptureFaceDataCond>';

        $response = Http::withDigestAuth("admin", "M12345678t")
            ->withHeaders([
                "Content-Type" => "text/xml;charset=utf-8"
            ])->send("POST", "http://192.168.11.204/ISAPI/AccessControl/CaptureFingerPrint", [
                "body" => $data
            ])->json();
        ## ## ## ## ## ## ## ##

//        $response = Http::withDigestAuth("admin", "M12345678t")
////            ->get("http://192.168.11.204/ISAPI/Intelligent/FDLib/capabilities?format=json")
////            ->post("http://192.168.11.204/ISAPI/Intelligent/FDLib?format=json", $data)
//            ->post("http://192.168.11.204/ISAPI/AccessControl/CaptureFaceData", $data)
//            ->json();

        dd($response);
//        dd($response["AcsEvent"]);
    }
}
