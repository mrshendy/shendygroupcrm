<?php

namespace App\Http\Livewire\Attendance;

use Livewire\Component;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Check extends Component
{
    public $attendanceToday;

    public function mount()
    {
        $employeeId = Auth::user()->employee_id ?? null;
        

        $this->attendanceToday = Attendance::where('employee_id', $employeeId)
            ->whereDate('attendance_date', Carbon::today())
            ->first();
    }

   public function checkIn()
{
    $employeeId = Auth::user()->employee_id; // أو ->employee->id لو عندك علاقة

    if (!$employeeId) {
        session()->flash('error', 'لا يوجد موظف مرتبط بهذا الحساب.');
        return;
    }

    $this->attendanceToday = Attendance::create([
        'employee_id' => $employeeId,
        'check_in' => now(),
        'attendance_date' => today(),
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
