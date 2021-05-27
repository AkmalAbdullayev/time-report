<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "first_name", "last_name", "middle_name",
        "description", "company_id", "branch_id", "finger_data",
        "department_id", "position_id", "image", "phone", "schedule_id", 'telegram_id'
    ];

    protected $table = "employees";
    //    protected $perPage = "10";

    public $interval;

    protected $appends = [
        "fingersCount",
        "day_count",
        "work_time"
    ];

    ## Relationships

    public function companies()
    {
        return $this->belongsTo(Company::class, "company_id")->withDefault();
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, "branch_id")->withDefault();
    }

    public function departments()
    {
        return $this->belongsTo(Department::class, "department_id")->withDefault();
    }

    public function positions()
    {
        return $this->belongsTo(Position::class, "position_id")->withDefault();
    }

    public function doors()
    {
        return $this->belongsToMany(Door::class, "employee_door", "employee_id", "doors_id")
            ->withPivot("employee_device_status");
    }

    public function doors2()
    {
        return $this->hasMany('App\Models\EmployeeDoor', 'employee_id');
    }

    public function comeOuts()
    {
        return $this->hasMany(ComeOut::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class)->withDefault([
            "name" => "График не задано."
        ]);
    }

    public function fingers()
    {
        return $this->hasMany(EmployeeFinger::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    ## Accessors

    public function username()
    {
        return $this->last_name . " " . ucfirst($this->first_name[0]) . ". " . ucfirst($this->middle_name[0]) . ".";
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name} {$this->middle_name}";
    }

    public function getShortFullNameAttribute()
    {
        return $this->first_name . " " . ($this->last_name[0] ?? '') . ". " . ($this->middle_name[0] ?? '');
    }

    public function getFirstLastNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFingersCountAttribute()
    {
        return $this->fingers()->count();
    }

    public function getDayCountAttribute()
    {
        return $this->comeOuts->pluck("action_time")->map(function ($item) {
            return date("Y-m-d", strtotime($item));
        })->unique()->values()->count();
    }

    public function getWorkTimeAttribute()
    {
        $times = $this->comeOuts;
        $seconds = 0;
        $weekBreaks = WeekBreak::where('active', 1)->get();
        $schedule = Schedule::find($this->schedule_id);
        $range_to = $schedule ? $schedule->range_to : '18:00:00';

        foreach ($times as $key => $time) {
            if ($key == 0 && $time->doorDevice->is_come == 1) {
                if (date('Y-m-d') == date('Y-m-d', strtotime($time->action_time)) &&
                    date('Y-m-d H:i:s') > $time->action_time) {

                    if (date('H:i') < $range_to) {
                        foreach ($weekBreaks as $weekBreak) {
                            if ($weekBreak->day == date('w', strtotime($time->action_time))) {
                                $seconds += strtotime(date('Y-m-d', strtotime($weekBreak->from)) . ' ' . date('H:i:s')) -
                                    strtotime($weekBreak->to);
                            }
                        }
                        $seconds += strtotime(date('Y-m-d', strtotime($time->action_time)) . ' ' . date('H:i:s')) -
                            strtotime($time->action_time);
                    } else
                        $seconds += strtotime(date('Y-m-d', strtotime($time->action_time)) . ' ' . $range_to . ':00') -
                            strtotime($time->action_time);
                } else
                    $seconds += strtotime(date('Y-m-d', strtotime($time->action_time)) . ' ' . $range_to . ':00') -
                        strtotime($time->action_time);
            } elseif (isset($times[$key + 1]) && $time->doorDevice->is_come == 0 &&
                $times[$key + 1]->doorDevice->is_come == 1 &&
                date('Y-m-d', strtotime($time->action_time)) == date('Y-m-d', strtotime($times[$key + 1]->action_time))) {
                foreach ($weekBreaks as $weekBreak) {
                    if ($weekBreak->day == date('w', strtotime($time->action_time))) {
                        $seconds += strtotime(date('Y-m-d', strtotime($weekBreak->from)) . ' ' . date('H:i:s')) -
                            strtotime($weekBreak->to);
                        if ($time->action_time > $weekBreak->from && $times[$key + 1]->action_time < $weekBreak->to)
                            $seconds -= strtotime(date('Y-m-d', strtotime($time->action_time)) . ' ' . date('H:i:s')) -
                                strtotime($time->action_time);
                    }
                }
                $seconds += strtotime($time->action_time) - strtotime($times[$key + 1]->action_time);
            }
        }

        return secToTime($seconds);
    }

    public static function normDay($from, $to)
    {
        $holidays = Holiday::select('start_date', 'number_of_days', 'repeat_annually')->get();
        $weekendExcludes = WeekendExclude::all()->pluck('date')->toArray();
        $attendanceSettings = AttendanceSetting::all()->pluck('weekend')->toArray();
        $normDay = 0;

        $period = new \DatePeriod(
            new \DateTime($from),
            new \DateInterval('P1D'),
            new \DateTime($to)
        );

        foreach ($period as $key => $value) {
            $date = $value->format('Y-m-d');
            $week = date('w', strtotime($date));

            if (in_array($date, $weekendExcludes))
                $normDay++;
            elseif (!in_array($week, $attendanceSettings)){
                if (count($holidays) > 0) {
                    foreach ($holidays as $holiday)
                        if ($holiday->repeat_annually != 1) {
                            if ($date < $holiday->start_date || $date >= date('Y-m-d', strtotime($holiday->start_date . ' +' . $holiday->number_of_days . ' days')))
                                $normDay++;
                        } else{
                            if ($date < date('Y-', strtotime($from)) . date('m-d', strtotime($holiday->start_date)) ||
                                $date >= date('Y-m-d', strtotime(date('Y-', strtotime($from)) . date('m-d', strtotime($holiday->start_date . ' +' . $holiday->number_of_days . ' days')))))
                                $normDay++;
                        }
                } else
                    $normDay++;
            }
        }

        return $normDay;
    }

    public function getNumOfEarlyInAttribute()
    {
        return $this->comeOuts->pluck("action_time")->map(function ($item) {
            $arrivalTime = date("H:i", strtotime($item));
            if ($arrivalTime < $this->schedule->range_from) {
                return [
                    "date" => date("Y-m-d", strtotime($item)),
                    "time" => date("H:i:s", strtotime($item))
                ];
            }
            return null;
        })->unique("date")->whereNotNull()->count();
    }

    public function getAbsentCountAttribute()
    {
        return $this->comeOuts->pluck("action_time")->map(function ($item) {
            $arrivalTime = date("H:i", strtotime($item));
            if ($arrivalTime > $this->schedule->range_from) {
                return [
                    "date" => date("Y-m-d", strtotime($item)),
                    "time" => date("H:i:s", strtotime($item))
                ];
            }
            return null;
        })->unique("date")->whereNotNull()->count();
    }

    public function getWorkingHoursAttribute()
    {
        $range_from = $this->schedule->range_from;
        $range_to = $this->schedule->range_to;

        return DB::table("come_outs")
            ->selectRaw("(DATE_FORMAT(action_time, '%H:%i:%s')) as time")
            ->where("employee_id", "=", $this->id)
            ->get();
    }

    ## SCOPES ##
    public function scopeAbsent()
    {
        $currentDate = now()->format("Y-m-d");
        return DB::table("employees")
            ->join("schedules", "employees.schedule_id", "=", "schedules.id")
            ->join("companies", "employees.company_id", "=", "companies.id")
            ->join("branches", "employees.branch_id", "=", "branches.id")
            ->join("departments", "employees.department_id", "=", "departments.id")
            ->join("positions", "employees.position_id", "=", "positions.id")
            ->whereNotIn("employees.id", DB::table("come_outs")
                ->select("employee_id")
                ->where("action_time", "like", "{$currentDate}%")
                ->groupBy("employee_id")
            )
            ->select([
                "employees.*",
                "schedules.name as schedule",
                "companies.name as company_name",
                "branches.name as branch_name",
                "departments.name as department_name",
                "positions.name as position_name"
            ]);
    }
}
