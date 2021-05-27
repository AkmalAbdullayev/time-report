<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;

class ComeOut extends Component
{
    public int $attendance_status = 0;

    public function render()
    {
        return view('livewire.come-out');
    }
}
