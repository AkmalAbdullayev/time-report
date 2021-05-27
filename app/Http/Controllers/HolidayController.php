<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSetting;
use App\Models\Holiday;
use App\Models\WeekendExclude;
use Illuminate\Http\Request;

class HolidayController extends Controller
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
                'type' => 'required|integer',
                'name' => 'required|string|min:3',
                'start_date' => 'required|date',
                'number_of_days' => 'required|integer'
            ]);
            try {
                Holiday::create([
                    'type' => $request->type,
                    'name' => $request->name,
                    'start_date' => date('Y-m-d', strtotime($request->start_date)),
                    'number_of_days' => $request->number_of_days,
                    'calculate_as_overtime' => 1,
                    'repeat_annually' => $request->repeat_annually ?? 0
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
                'holiday_id' => 'required|integer',
                'type' => 'required|integer',
                'name' => 'required|string|min:3',
                'start_date' => 'required|date',
                'number_of_days' => 'required|integer'
            ]);
            try {
                $holiday = Holiday::find($request->holiday_id);
                if (!$holiday){
                    return redirect(route('weekend'))
                        ->with('error', 'Дверь не найден');
                }
                $holiday->update([
                    'type' => $request->type,
                    'name' => $request->name,
                    'start_date' => date('Y-m-d', strtotime($request->start_date)),
                    'number_of_days' => $request->number_of_days,
                    'repeat_annually' => $request->repeat_annually ? 1 : 0
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
                'holiday_id' => 'required',
            ]);
            try {
                $holiday_id = base64_decode($request->holiday_id);
                $holiday = Holiday::find($holiday_id);
                if (!$holiday){
                    return redirect(route('weekend'))
                        ->with('error', 'Дверь не найден');
                }
                $holidays = Holiday::all();
                $weekendExcludes = WeekendExclude::all();
                $attendanceSettings = AttendanceSetting::all();
                $weekDays = getWeekDays();
                $authenticationModes = getAuthenticationModes();
                return view('fp.weekend.index', compact('holidays', 'weekendExcludes', 'attendanceSettings', 'holiday', 'weekDays', 'authenticationModes'));
            }catch (\Exception $exception){
                return redirect(route('weekend'))
                    ->with('error', $exception->getMessage());
            }
        }
        elseif ($request->filled('method') && $request->method == 'delete'){
            $request->validate([
                'holiday_id' => 'required|integer',
            ]);
            try {
                $holiday = Holiday::find($request->holiday_id);
                if ($holiday){
                    $holiday->delete();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
