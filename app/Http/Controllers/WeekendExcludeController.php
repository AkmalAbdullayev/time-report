<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSetting;
use App\Models\Holiday;
use App\Models\WeekendExclude;
use Illuminate\Http\Request;

class WeekendExcludeController extends Controller
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
                'date' => 'required|date',
                'description' => 'string|min:3'
            ]);
            try {
                WeekendExclude::create([
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'description' => $request->description
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
                'weekend_exclude_id' => 'required|integer',
                'date' => 'required|date',
                'description' => 'string|min:3'
            ]);
            try {
                $weekendExclude = WeekendExclude::find($request->weekend_exclude_id);
                if (!$weekendExclude){
                    return redirect(route('weekend'))
                        ->with('error', 'Дверь не найден');
                }
                $weekendExclude->update([
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'description' => $request->description
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
                'weekend_exclude_id' => 'required',
            ]);
            try {
                $weekend_exclude_id = base64_decode($request->weekend_exclude_id);
                $weekendExclude = WeekendExclude::find($weekend_exclude_id);
                if (!$weekendExclude){
                    return redirect(route('weekend'))
                        ->with('error', 'Дверь не найден');
                }
                $holidays = Holiday::all();
                $weekendExcludes = WeekendExclude::all();
                $attendanceSettings = AttendanceSetting::all();
                $weekDays = getWeekDays();
                $authenticationModes = getAuthenticationModes();
                return view('fp.weekend.index', compact('holidays', 'weekendExcludes', 'attendanceSettings', 'weekendExclude', 'weekDays', 'authenticationModes'));
            }catch (\Exception $exception){
                return redirect(route('weekend'))
                    ->with('error', $exception->getMessage());
            }
        }
        elseif ($request->filled('method') && $request->method == 'delete'){
            $request->validate([
                'weekend_exclude_id' => 'required|integer',
            ]);
            try {
                $weekendExclude = WeekendExclude::find($request->weekend_exclude_id);
                if ($weekendExclude){
                    $weekendExclude->delete();
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
     * @param  \App\Models\WeekendExclude  $weekendExclude
     * @return \Illuminate\Http\Response
     */
    public function show(WeekendExclude $weekendExclude)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WeekendExclude  $weekendExclude
     * @return \Illuminate\Http\Response
     */
    public function edit(WeekendExclude $weekendExclude)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WeekendExclude  $weekendExclude
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WeekendExclude $weekendExclude)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WeekendExclude  $weekendExclude
     * @return \Illuminate\Http\Response
     */
    public function destroy(WeekendExclude $weekendExclude)
    {
        //
    }
}
