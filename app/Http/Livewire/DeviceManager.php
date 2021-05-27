<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DeviceManager extends Component
{
    public function render()
    {
        return view('livewire.device-manager')
            ->extends("layouts.master")
            ->section("content");
    }
}
