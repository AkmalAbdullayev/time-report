<?php

namespace App\Http\Controllers;

use App\Models\Door;
use App\Models\DoorDevice;
use Illuminate\Http\Request;

class DoorController extends Controller
{
    public Door $model;

    public function __construct(Door $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return view("fp.doors.index");
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => "required|integer",
            "devices" => "required",
            "in_out" => "required"
        ]);

        $door = $this->model->findOrFail(request('name'));
        if (!$door){
            session()->flash("errorMessage", "Дверь не найден...");
            return back();
        }
        if (!empty($request->input("devices"))) {
            foreach ($request->input("devices") as $device) {
                $door_device = new DoorDevice();
                $door_device->doors_id = $door->id;
                $door_device->device_id = $device;
                $door_device->added_at = now();
                $door_device->is_come = $request->input("in_out");
                $door_device->save();
            }
            session()->flash("message", "Добавлено Успешно");
        }
        return back();
    }

    public function show(Door $doors)
    {
        //
    }

    public function edit(Door $doors)
    {
        abort(404);
    }

    public function update(Request $request, Door $doors)
    {
        abort(404);
    }

    public function destroy(Door $doors)
    {
        abort(404);
    }

    public function door()
    {
        $doors = $this->model->orderBy('name')->paginate(20);

        if (\request()->filled('method') && \request('method') == 'create'){
            \request()->validate([
                'title' => 'required|string|unique:doors,name|min:3',
            ]);
            try {
                $this->model->create(['name' => \request('title')]);
                return redirect(route('door.crud'))
                    ->with('success', 'Успешно добавлено');
            }catch (\Exception $exception){
                return redirect(route('door.crud'))
                    ->with('error', $exception->getMessage());
            }
        }
        if (\request()->filled('method') && \request('method') == 'update'){
            \request()->validate([
                'door_id' => 'required|integer|exists:doors,id',
            ]);
            try {
                $door = $this->model->find(\request('door_id'));
                if (!$door){
                    return redirect(route('door.crud'))
                        ->with('error', 'Дверь не найден');
                }
                $door->update(['name' => \request('title')]);
                return redirect(route('door.crud'))
                    ->with('success', 'Успешно обновлено');
            }catch (\Exception $exception){
                return redirect(route('door.crud'))
                    ->with('error', $exception->getMessage());
            }
        }
        if (\request()->filled('method') && \request('method') == 'edit'){
            \request()->validate([
                'door_id' => 'required',
            ]);
            try {
                $door_id = base64_decode(\request('door_id'));
                $door = Door::find($door_id);
                if (!$door){
                    return redirect(route('door.crud'))
                        ->with('error', 'Дверь не найден');
                }
                return view('fp.doors.door', compact('doors', 'door'));
            }catch (\Exception $exception){
                return redirect(route('door.crud'))
                    ->with('error', $exception->getMessage());
            }
        }
        if (\request()->filled('method') && \request('method') == 'delete'){
            \request()->validate([
                'door_id' => 'required|integer|exists:doors,id',
            ]);
            try {
                $door = Door::find(\request('door_id'));
                if ($door){
                    $door->delete();
                    return redirect(route('door.crud'))
                        ->with('success', 'Успешно удален');
                }
                return redirect(route('door.crud'))
                    ->with('error', 'Дверь не найден');
            }catch (\Exception $exception){
                return redirect(route('door.crud'))
                    ->with('error', $exception->getMessage());
            }
        }
        return view('fp.doors.door', compact('doors'));
    }
}
