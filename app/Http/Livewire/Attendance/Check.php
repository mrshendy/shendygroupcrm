<?php

namespace App\Http\Livewire\Attendance;

use Livewire\Component;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Check extends Component
{
    public $attendanceToday;
    public $employee_id;

    public function mount()
    {
        // مثال: ربط الموظف بالمستخدم الحالي
        $this->employee_id = Auth::user()->employee_id ?? null;

        $this->attendanceToday = Attendance::where('employee_id', $this->employee_id)
            ->whereDate('attendance_date', Carbon::today())
            ->first();
    }

    public function checkIn()
    {
        $this->attendanceToday = Attendance::create([
            'employee_id' => $this->employee_id,
            'check_in' => Carbon::now(),
            'attendance_date' => Carbon::today(),
        ]);
    }

    public function checkOut()
    {
        if ($this->attendanceToday && !$this->attendanceToday->check_out) {
            $this->attendanceToday->update([
                'check_out' => Carbon::now(),
                'hours' => $this->attendanceToday->calculateHours(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.attendance.check');
    }
}
