<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceRequest;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        return view("fp.devices.index");
    }

    public function create()
    {
        //
    }

    public function store(DeviceRequest $request)
    {
        //
    }

    public function show(Device $device)
    {
        //
    }

    public function edit(Device $device)
    {
        //
    }

    public function update(Request $request, Device $device)
    {
        //
    }

    public function destroy(Device $device)
    {
        //
    }
}
