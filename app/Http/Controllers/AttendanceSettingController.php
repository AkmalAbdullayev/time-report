<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSetting;
use App\Models\Holiday;
use App\Models\WeekendExclude;
use Illuminate\Http\Request;

class AttendanceSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector|void
     */
    public function index(Request $request)
    {
        if ($request->filled('method') && $request->method == 'create'){
            $request->validate([
                'weekend' => 'required||min:0|max:6',
                'calculate_at' => 'date',
                'authentication_mode' => 'integer|required'
            ]);
            try {
                AttendanceSetting::create([
                    'weekend' => $request->weekend,
                    'calculate_at' => date('Y-m-d', strtotime($request->calculate_at)),
                    'authentication_mode' => $request->authentication_mode
                ]);
                return redirect(route('weekend'))
                    ->with('success', 'Успешно добавлено');
            }catch (\Exception $exception){
                return redirect(route('weekend'))
                    ->with('error', $exception->getMessage());
            }
        }
        elseif ($request->filled('method') && $request->method == 'update'){
            $request->validate([
                'attendance_setting_id' => 'required|integer',
                'weekend' => 'required||min:0|max:6',
                'calculate_at' => 'date',
                'authentication_mode' => 'integer|required'
            ]);
            try {
                $attendanceSetting = AttendanceSetting::find($request->attendance_setting_id);
                if (!$attendanceSetting){
                    return redirect(route('weekend'))
                        ->with('error', 'Дверь не найден');
                }
                $attendanceSetting->update([
                    'weekend' => $request->weekend,
                    'calculate_at' => date('Y-m-d', strtotime($request->calculate_at)),
                    'authentication_mode' => $request->authentication_mode
                ]);
                return redirect(route('weekend'))
                    ->with('success', 'Успешно обновлено');
            }catch (\Exception $exception){
                return redirect(route('weekend'))
                    ->with('error', $exception->getMessage());
            }
        }
        elseif ($request->filled('method') && $request->method == 'edit'){
            $request->validate([
                'attendance_setting_id' => 'required',
            ]);
            try {
                $attendance_setting_id = base64_decode($request->attendance_setting_id);
                $attendanceSetting = AttendanceSetting::find($attendance_setting_id);
                if (!$attendanceSetting){
                    return redirect(route('weekend'))
                        ->with('error', 'Дверь не найден');
                }
                $holidays = Holiday::all();
                $weekendExcludes = WeekendExclude::all();
                $attendanceSettings = AttendanceSetting::all();
                $weekDays = getWeekDays();
                $authenticationModes = getAuthenticationModes();
                return view('fp.weekend.index', compact('holidays', 'weekendExcludes', 'attendanceSettings', 'attendanceSetting', 'weekDays', 'authenticationModes'));
            }catch (\Exception $exception){
                return redirect(route('weekend'))
                    ->with('error', $exception->getMessage());
            }
        }
        elseif ($request->filled('method') && $request->method == 'delete'){
            $request->validate([
                'attendance_setting_id' => 'required|integer',
            ]);
            try {
                $attendanceSetting = AttendanceSetting::find($request->attendance_setting_id);
                if ($attendanceSetting){
                    $attendanceSetting->delete();
                    return redirect(route('weekend'))
                        ->with('success', 'Успешно удален');
                }
                return redirect(route('weekend'))
                    ->with('error', 'Дверь не найден');
            }catch (\Exception $exception){
                return redirect(route('weekend'))
                    ->with('error', $exception->getMessage());
            }
        }else
            return redirect(route('weekend'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AttendanceSetting  $attendanceSetting
     * @return \Illuminate\Http\Response
     */
    public function show(AttendanceSetting $attendanceSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AttendanceSetting  $attendanceSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(AttendanceSetting $attendanceSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AttendanceSetting  $attendanceSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AttendanceSetting $attendanceSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AttendanceSetting  $attendanceSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttendanceSetting $attendanceSetting)
    {
        //
    }
}
