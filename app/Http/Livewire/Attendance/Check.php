<?php

namespace App\Http\Livewire\Attendance;

use Livewire\Component;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Check extends Component
{
    public $attendanceToday;

    public function mount()
    {
        $employeeId = Auth::user()->employee_id ?? null;

        if ($employeeId) {
            $this->attendanceToday = Attendance::where('employee_id', $employeeId)
                ->whereDate('attendance_date', Carbon::today())
                ->first();
        }
    }

    public function checkIn()
    {
        $employeeId = Auth::user()->employee_id;

        if (!$employeeId) {
            session()->flash('error', 'لا يوجد موظف مرتبط بهذا الحساب.');
            return;
        }

        if ($this->attendanceToday) {
            session()->flash('error', 'تم تسجيل حضورك بالفعل اليوم.');
            return;
        }

        $this->attendanceToday = Attendance::create([
            'employee_id'    => $employeeId,
            'check_in'       => now(),
            'attendance_date'=> today(),
        ]);

        session()->flash('success', 'تم تسجيل الحضور بنجاح.');
    }

    public function checkOut()
    {
        if (!$this->attendanceToday) {
            session()->flash('error', 'لم يتم تسجيل حضور اليوم.');
            return;
        }

        if ($this->attendanceToday->check_out) {
            session()->flash('error', 'تم تسجيل الانصراف بالفعل.');
            return;
        }

        $checkIn  = Carbon::parse($this->attendanceToday->check_in);
        $checkOut = now();
        $hours    = $checkIn->diffInHours($checkOut);

        $this->attendanceToday->update([
            'check_out' => $checkOut,
            'hours'     => $hours,
        ]);

        session()->flash('success', 'تم تسجيل الانصراف بنجاح.');
    }

    public function render()
    {
        return view('livewire.attendance.check');
    }
}
