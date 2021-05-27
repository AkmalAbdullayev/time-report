<?php

namespace App\Console\Commands;

use App\helpers\AttendanceManagement;
use App\Models\ComeOut;
use App\Models\Device;
use App\Models\DoorDevice;
use App\Models\EmployeeDoor;
use DateTime;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

class AttendanceCron extends Command
{
    protected $attendance_management;
    protected $comeOut;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'test:cron {--from=? : Manual date from} {--to=? : Manual date to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data from the api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->attendance_management = new AttendanceManagement();
        $this->comeOut = new ComeOut();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $ip_addresses = Device::all();
        $searchResultPosition = 0;

        foreach ($ip_addresses as $device) {
            $deviceStatus = $this->attendance_management->checkDeviceStatus($device);
            $doorDeviceId = DoorDevice::where("device_id", $device->id)->first();
            if (isset($doorDeviceId->id)) {
                $comeOutModel = ComeOut::where("doors_has_device_id", $doorDeviceId->id)->orderBy("action_time", "desc")->first();
            } else {
                continue;
            }

            if ($deviceStatus === 200) {
                print_r($device->ip . " - STATUS OK!!!" . PHP_EOL);

                if (is_null($comeOutModel)) {
                    $come_outs = $this->attendance_management->comeOuts($device); ## upcoming data ##
                } else {
                    $come_outs = $this->attendance_management->comeOuts($device, 0, date("Y-m-d", strtotime($comeOutModel->action_time)));
                }

                if ($come_outs != false) {
                    if (array_key_exists("AcsEvent", $come_outs) && $come_outs["AcsEvent"]["numOfMatches"] !== 0) {
                        $totalMatches = (int)round($come_outs["AcsEvent"]["totalMatches"] / $come_outs["AcsEvent"]["numOfMatches"]); ## get number of pages ##

                        while ($totalMatches--) {
                            if ($come_outs["AcsEvent"]["numOfMatches"] !== 0) {
                                foreach ($come_outs["AcsEvent"]["InfoList"] as $come_out) {
                                    $dt = new DateTime($come_out["time"]);
                                    $getDateFromUTC = intval($dt->getTimezone()->getName());

                                    $employeeDoor = EmployeeDoor::where("employee_id", $come_out["employeeNoString"])->first();
                                    if (!is_null($employeeDoor)) {
                                        try {
                                            if (ComeOut::where("doors_has_device_id", $doorDeviceId->id)->where("event_serial_no", $come_out["serialNo"])->doesntExist()) {
                                                $comeOut = new ComeOut();
                                                $comeOut->doors_has_device_id = $doorDeviceId->id;
                                                $comeOut->employee_id = $come_out["employeeNoString"];
                                                $comeOut->action_time = date("Y-m-d H:i:s", strtotime($come_out["time"] . "+ {$getDateFromUTC} hours"));
                                                $comeOut->event_serial_no = $come_out["serialNo"];

                                                $comeOut->save();

                                                print_r("INSERTED" . PHP_EOL);
                                            }
                                        } catch (QueryException $exception) {
                                            if ($exception->getCode() === 23000) {
                                                continue;
                                            }
                                        }
                                    }
                                }
                                $searchResultPosition += 10;

                                if (is_null($comeOutModel)) {
                                    $come_outs = $this->attendance_management->comeOuts($device, $searchResultPosition);
                                } else {
                                    $come_outs = $this->attendance_management->comeOuts($device, $searchResultPosition, date("Y-m-d", strtotime($comeOutModel->action_time)));
                                }

                            }
                        }
                    }
                } else {
                    $this->comment("Device Not Active");
                }
            } else {
                print_r($device->ip . " - STATUS NOT ACTIVE!!!" . PHP_EOL);
            }
        }




        // old
//        $ip_addresses = Device::all();
//        $searchResultPosition = 0;
//
//        foreach ($ip_addresses as $device) {
//            $deviceStatus = $this->attendance_management->checkDeviceStatus($device);
//            $doorDeviceId = DoorDevice::where("device_id", $device->id)->first();
//            if (isset($doorDeviceId->id)) {
//                $comeOutModel = ComeOut::where("doors_has_device_id", $doorDeviceId->id)->orderBy("action_time", "desc")->first();
//            } else {
//                continue;
//            }
//
//            if ($deviceStatus === 200) {
//                print_r(PHP_EOL . $device->ip . " - STATUS OK!!!" . PHP_EOL);
//
//                if ($this->option("from") === '?') {
//                    if (is_null($comeOutModel)) {
//                        $come_outs = $this->attendance_management->comeOuts($device); ## upcoming data ##
//                    } else {
//                        $come_outs = $this->attendance_management->comeOuts($device, 0, date("Y-m-d", strtotime($comeOutModel->action_time)));
//                    }
//                } else {
//                    if ($this->option("to") === '?') {
//                        $come_outs = $this->attendance_management->comeOuts($device, 0, date("Y-m-d", strtotime($this->option("from"))));
//                    } else {
//                        $come_outs = $this->attendance_management->comeOuts($device, 0, date("Y-m-d", strtotime($this->option("from"))), $this->option('to'));
//                    }
//                }
//
//                if ($come_outs != false) {
//                    if (array_key_exists("AcsEvent", $come_outs) && $come_outs["AcsEvent"]["numOfMatches"] !== 0) {
//                        $totalMatches = (int)round($come_outs["AcsEvent"]["totalMatches"] / $come_outs["AcsEvent"]["numOfMatches"]); ## get number of pages ##
//                        $bar = $this->output->createProgressBar($totalMatches);
//
//                        while ($totalMatches--) {
//                            $bar->start();
//                            if ($come_outs["AcsEvent"]["numOfMatches"] !== 0) {
//                                foreach ($come_outs["AcsEvent"]["InfoList"] as $come_out) {
//                                    $dt = new DateTime($come_out["time"]);
//                                    $getDateFromUTC = intval($dt->getTimezone()->getName());
//
//                                    $employeeDoor = EmployeeDoor::where("employee_id", $come_out["employeeNoString"])->first();
//                                    if (!is_null($employeeDoor)) {
//                                        try {
//                                            if (ComeOut::with(["doorDevice" => function ($q) use ($device) {
//                                                $q->where("device_id", $device->id);
//                                            }])->where("event_serial_no", $come_out["serialNo"])->doesntExist()) {
//                                                $comeOut = new ComeOut();
//                                                $comeOut->doors_has_device_id = $doorDeviceId->id;
//                                                $comeOut->employee_id = $come_out["employeeNoString"];
//                                                $comeOut->action_time = date("Y-m-d H:i:s", strtotime($come_out["time"] . "+ {$getDateFromUTC} hours"));
//                                                $comeOut->event_serial_no = $come_out["serialNo"];
//
//                                                $comeOut->save();
//
//                                                print_r("INSERTED" . PHP_EOL);
//                                            }
//                                        } catch (QueryException $exception) {
//                                            if ($exception->getCode() === 23000) {
//                                                info("Error Message : {$exception->getMessage()}");
//                                                continue;
//                                            }
//                                        }
//                                    }
//                                }
//                                $searchResultPosition += 10;
//
//                                if (is_null($comeOutModel)) {
//                                    $come_outs = $this->attendance_management->comeOuts($device, $searchResultPosition);
//                                } else {
//                                    $come_outs = $this->attendance_management->comeOuts($device, $searchResultPosition, date("Y-m-d", strtotime($comeOutModel->action_time)));
//                                }
//
//                            }
//                            $bar->advance();
//                        }
//
//                        $bar->finish();
//                    }
//                } else {
//                    $this->comment("Device Not Active");
//                }
//            } else {
//                print_r($device->ip . " - STATUS NOT ACTIVE!!!" . PHP_EOL);
//                continue;
//            }
//        }
    }
}
