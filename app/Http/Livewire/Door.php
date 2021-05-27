<?php

namespace App\Http\Livewire;

use App\Models\DoorDevice;
use Livewire\Component;

class Door extends Component
{
    public \App\Models\Door $door_model;
    public \App\Models\Device $device_model;
    public DoorDevice $doorDevice;

    protected $listeners = ["destroy"];

    public array $door = [];
    public array $device = [];

    public function mount(\App\Models\Door $door, \App\Models\Device $device, DoorDevice $doorDevice)
    {
        $this->door_model = $door;
        $this->device_model = $device;
        $this->doorDevice = $doorDevice;
    }

    public function render()
    {
        $this->dispatchBrowserEvent("select2");
        $devices = $this->device_model->get();
        $available_devices = [];
        foreach ($devices as $device){
            if (DoorDevice::where('device_id', $device->id)->doesntExist()){
                $available_devices[] = $device;
            }
        }
        $door_devices = $this->doorDevice::with("device")
            ->orderBy('device_id')
            ->get();


        return view('livewire.door', [
            "devices" => $available_devices,
            "doors" => $this->door_model->get(),
            "door_devices" => $door_devices
        ]);
    }

    public function edit($door_id, $device_id)
    {
        if (isset($door_id)) {
            $door = $this->door_model->findOrFail($door_id);
            $door_device = $this->doorDevice->where("doors_id", $door_id)->where("device_id", $device_id)->first();

            $this->door["device_id"] = $door->id;
            $this->door["device_name"] = $door->name;
            $this->door["id"] = $door_id;
            $this->device["id"] = $door_device->device_id;
        }
    }

    public function clear()
    {
        $this->reset("door");
        $this->reset("device");
    }

    public function update()
    {
        if (isset($this->door["device_id"])) {
            $door = $this->door_model->findOrFail($this->door["device_id"]);
            $isUpdated = $door->update([
                "name" => $this->door["device_name"]
            ]);

            if ($isUpdated) {
                $this->dispatchBrowserEvent("updateModel");
            }

            if (is_array($this->device["id"])) {
                foreach ($this->device["id"] as $device) {
                    if (!$this->doorDevice->where("doors_id", $door->id)->where("device_id", $device)->exists()) {
                        $model = $this->doorDevice->where("doors_id", $door->id);
                        $model->updateOrCreate([
                            "doors_id" => $door->id,
                            "device_id" => $device,
                            "is_come" => 1,
                            "added_at" => now()
                        ]);
                    }
                }
            }
        }

        $this->dispatchBrowserEvent("closeModal");
    }

    public function deleteConfirm($door_id, $device_id)
    {
        $this->dispatchBrowserEvent("confirm-delete", [
            "type" => "warning",
            "title" => "Вы уверены?",
            "text" => "",
            "door_id" => $door_id,
            "device_id" => $device_id
        ]);
    }

    public function destroy($door_id, $device_id)
    {
        if (isset($door_id)) {
            $this->doorDevice->where("doors_id", $door_id)->where("device_id", $device_id)->delete();

            $this->dispatchBrowserEvent("select2");


            session()->flash("message", "Удалено Успешно");

        } else {
            session()->flash("message", "Шаблон не найдено");
        }
    }
}
