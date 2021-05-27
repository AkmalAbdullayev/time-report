<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Device extends Component
{
    public $model, $parent = [], $device_name, $device_ip, $device_login = 'admin', $device_password = 'M12345678t', $all;
    public $deviceStatus = [];
    protected $listeners = ["destroy", "deviceStatusEvent"];

    public function mount(\App\Models\Device $device)
    {
        $this->model = $device;
        $this->all = $this->model->all();
    }

    public function render()
    {
        return view('livewire.device', [
            "devices" => $this->all,
        ]);
    }

    public function clear()
    {
        $this->reset("device_name", "device_ip", "device_login", "device_password");
    }

    public function store()
    {
        $this->validate([
            "device_name" => "required|string",
            "device_ip" => "required|string",
            "device_login" => "required|string",
            "device_password" => "required|string",
        ]);

        $this->model->create([
            "name" => $this->device_name,
            "ip" => $this->device_ip,
            "login" => $this->device_login,
            "password" => $this->device_password,
            'status' => 1
        ]);

        $this->dispatchBrowserEvent("sweety:create", [
            "type" => "success",
            "title" => "Устройство успешно добавлено!",
            "text" => ""
        ]);

        $this->reset("device_name", "device_ip", "device_login", "device_password", "device_status", "device_activity");

        return back();
    }

    public function edit($id)
    {
        $device = $this->model->findOrFail($id);
        $this->parent = [
            "device_id" => $device->id,
            "device_name" => $device->name,
            "device_ip" => $device->ip,
            "device_login" => $device->login,
            "device_password" => $device->password,
//            "device_status" => $device->status,
//            "device_activity" => $device->active
        ];
    }

    public function update()
    {
        $this->validate([
            "parent.device_name" => "required|string",
            "parent.device_ip" => "required|string",
            "parent.device_login" => "required|string",
            "parent.device_password" => "required|string",
//            "parent.device_status" => "numeric|digits_between:0,1",
//            "parent.device_activity" => "numeric|digits_between:0,1"
        ]);

        if (isset($this->parent["device_id"])) {
            $device = $this->model->findOrFail($this->parent["device_id"]);
            $isUpdated = $device->update([
                "name" => $this->parent["device_name"],
                "ip" => $this->parent["device_ip"],
                "login" => $this->parent["device_login"],
                "password" => $this->parent["device_password"],
//                "status" => $this->parent["device_status"],
//                "active" => $this->parent["device_activity"]
            ]);

            if ($isUpdated) {
                $this->dispatchBrowserEvent("sweety:update");

                $this->dispatchBrowserEvent("refresh");
            }
        }
    }

    public function deleteConfirm($id)
    {
        $this->dispatchBrowserEvent("sweety:confirm-delete", [
            "type" => "warning",
            "title" => "Вы уверены?",
            "text" => "",
            "id" => $id
        ]);
    }

    public function destroy($id)
    {
        if (isset($id)) {
            $isDeleted = $this->model->find($id)->delete();

            if ($isDeleted) {
                $this->dispatchBrowserEvent("sweety:deleted", [
                    "type" => "success",
                    "title" => "Информация успешно удалено!!!",
                    "text" => ""
                ]);
            }
        }

        return back();
    }

    public function deviceStatusEvent()
    {
        foreach ($this->all as $all) {
            $deviceStatus = $all->checkDeviceStatus();
            array_push($this->deviceStatus, $deviceStatus);
        }
    }
}
