<?php

namespace App\Console\Commands;

use App\helpers\AttendanceManagement;
use App\Models\CloneComeOut;
use App\Models\ComeOut;
use App\Models\Device;
use App\Models\DoorDevice;
use App\Models\EmployeeDoor;
use DateTime;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class RecordData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'record';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Record data';

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
     * @throws Exception
     */
    public function handle()
    {
        $devices = Device::all();
        $attendanceManagement = new AttendanceManagement();

        foreach ($devices as $device) {
            $searchResultPosition = 0;
            $doorDeviceId = DoorDevice::where("device_id", $device->id)->first();
	    $last_date = ComeOut::where('device_id', $device->id)->orderBy('action_time', 'Desc')->first();
	    if ($last_date) {
	    	$last_date = date('Y-m-d', strtotime($last_date->action_time));
	    } else {
	    	$last_date = '2021-04-15';
	    } 

            $deviceStatus = $attendanceManagement->checkDeviceStatus($device);

            if ($deviceStatus === 200) {
                print_r($device->ip . " - STATUS ACTIVE!!!" . PHP_EOL);

                $responses = $attendanceManagement->cloneComeOuts($device, 0, $last_date);

                if (array_key_exists("AcsEvent", $responses) && $responses["AcsEvent"]["numOfMatches"] != 0) {
                    $totalMatches = (int)round($responses["AcsEvent"]["totalMatches"] / $responses["AcsEvent"]["numOfMatches"]);
//if ($device->id==2)			
//dd($device->id, $totalMatches,  $responses);
                    while ($totalMatches--) {
                        if (array_key_exists("AcsEvent", $responses) && $responses["AcsEvent"]["numOfMatches"] != 0 && array_key_exists("InfoList", $responses["AcsEvent"])) {
                            foreach ($responses["AcsEvent"]["InfoList"] as $response) {
//                                print_r($response["serialNo"] . PHP_EOL);

                                if (ComeOut::where("device_id", $device->id)->where("event_serial_no", $response["serialNo"])->doesntExist()) {
                                    $dt = new DateTime($response["time"]);
                                    $getDateFromUTC = intval($dt->getTimezone()->getName());

                                    $hasEmployee = EmployeeDoor::where("employee_id", $response["employeeNoString"])->exists();
                                    if ($hasEmployee) {
                                        try {
                                            $comeOut = new ComeOut();
                                            $comeOut->doors_has_device_id = $doorDeviceId->id;
                                            $comeOut->employee_id = $response["employeeNoString"];
                                            $comeOut->action_time = date("Y-m-d H:i:s", strtotime($response["time"] . "+ {$getDateFromUTC} hours"));
                                            $comeOut->event_serial_no = $response["serialNo"];
                                            $comeOut->device_id = $device->id;

                                            $comeOut->save();

                                            if (isset($comeOut->employees->telegram_id) && !empty($comeOut->employees->telegram_id)) {
                                            	try{
													$telegram_id = $comeOut->employees->telegram_id;
													$url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage?chat_id=".$telegram_id;
													$message = '';
													if ($doorDeviceId->is_come === 1) {
														$message = 'Siz '.$device->name.'dan soat '.date("H:i", strtotime($comeOut->action_time)).' ga kirdingiz';
													}else{
														$message = 'Siz '.$device->name.'dan soat '.date("H:i", strtotime($comeOut->action_time)).' da chiqdingiz';
													}
                									$url = $url . "&text=" . urlencode($message);
                									$resp = Http::get($url);
                									$resp = $resp->object();
                									if ($resp->ok) {
                										$comeOut->telegram_sent_at = now();
                										$comeOut->save();
                									}
                                            	}catch(\Exception $e){
                                            		\Log::info('telegram sent error: '.$e->getMessage());
                                            	}
                                            }else{
                                            	\Log::info('telegram id not found');
                                            }

                                            print_r("INSERTED. Event Serial No : {$response["serialNo"]}" . PHP_EOL);
                                        } catch (Exception $exception) {
                                            //
                                        }
                                    }
                                }
                            }

                            $searchResultPosition += 10;
                            $responses = $attendanceManagement->cloneComeOuts($device, $searchResultPosition, $last_date);
//                            if (is_null($device->latest_serial_no) || empty($device->latest_serial_no)) {
//
//                            } else {
//                                $lastComeOut = CloneComeOut::where("doors_has_device_id", $doorDeviceId->id)->where("event_serial_no", $device->latest_serial_no)->first();
//                                $toYear = date("Y-m-d", strtotime($lastComeOut->action_time));
//                                $responses = $attendanceManagement->cloneComeOuts($device, $searchResultPosition, $toYear);
//                            }
                        }
                    }
                }
            } else {
                print_r($device->ip . " - STATUS NOT ACTIVE!!!" . PHP_EOL);
            }
        }

        return 0;
    }
}
