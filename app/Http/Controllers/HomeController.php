<?php

namespace App\Http\Controllers;

use App\Models\ComeOut;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $mostAbsent = Employee::with(["companies", "branches", "departments", "positions"])
    		->take(5)
    		->get()
    		->sortByDesc("absentCount");

	    $mostNotAbsent = Employee::with(["companies", "branches", "departments", "positions"])
            ->take(5)
            ->get()
            ->sortByDesc("numOfEarlyIn");

	    $online = ComeOut::whereDate('action_time', '=', date('Y-m-d'))
			->get()
			->groupBy('employee_id')
			->count();

	    $offline = Employee::count() - $online;

        $companies = Company::with('employee')
            ->orderBy('name')
            ->limit(100)
            ->get();

        $companies_data = [];
        foreach ($companies as $key => $value) {
            $online_data = ComeOut::whereDate('action_time', '=', date('Y-m-d'))
            ->whereHas('employees', function($q) use($value){
                $q->where('company_id', $value->id);
            })
            ->get()
            ->groupBy('employee_id')
            ->count();
            $companies_data[$key]['company'] = $value;
            $companies_data[$key]['online'] = $online_data;
            $companies_data[$key]['offline'] = Employee::where('company_id', $value->id)->count() - $online_data;
            $companies_data[$key]['in_out'] = ComeOut::whereDate("action_time", now()->format("Y-m-d"))
                ->whereHas('employees', function($q) use($value){
                    $q->where('company_id', $value->id);
                })
                ->count();
        }



        return view('home', [
            "companies_data" => $companies_data,
            "offline" => $offline,
	        "online" => $online,
            "employees" => $mostAbsent,
	        "early_came" => $mostNotAbsent,
            "countInOuts" => ComeOut::countInOuts()
        ]);
    }

    public function users()
    {
        $users = User::all();
        return view("fp.users", [
            "users" => $users
        ]);
    }
}
