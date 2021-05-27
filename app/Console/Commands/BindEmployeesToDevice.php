<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;

class BindEmployeesToDevice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bind employee to the device!!!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Employee::with("doors2")->chunk(20, function ($employees) {
            foreach ($employees as $employee) {
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
                        "userVerifyMode" => ""
                    ]
                ];

                Http::withDigestAuth("admin", "M12345678t")
                    ->post("http://192.168.11.210/ISAPI/AccessControl/UserInfo/Record?format=json", $data)
                    ->json();
            }
        });

        return 0;
    }
}
