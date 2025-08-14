<?php

namespace App\Http\Livewire\Attendance;

use Livewire\Component;
use App\Models\Attendance;

class Manage extends Component
{
    public $attendances;

    public function mount()
    {
        $this->attendances = Attendance::with('employee')->latest()->get();
    }

    public function render()
    {
        return view('livewire.attendance.manage');
    }
}
