<?php

namespace App\Http\Livewire\Attendance;

use Livewire\Component;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Check extends Component
{
    public $attendanceToday = null;

    public function mount()
    {
        $employeeId = Auth::user()->employee_id ?? null;
        $tz = config('app.timezone', 'Africa/Cairo');

        if ($employeeId) {
            $this->attendanceToday = Attendance::where('employee_id', $employeeId)
                ->whereDate('attendance_date', Carbon::today($tz))
                ->first();
        }
    }

    public function checkIn()
    {
        $employeeId = Auth::user()->employee_id;
        $tz = config('app.timezone', 'Africa/Cairo');

        if (!$employeeId) {
            session()->flash('error', 'لا يوجد موظف مرتبط بهذا الحساب.');
            return;
        }

        try {
            DB::beginTransaction();

            $attendance = Attendance::firstOrCreate(
                [
                    'employee_id'     => $employeeId,
                    'attendance_date' => Carbon::today($tz)->toDateString(),
                ],
                [
                    'check_in' => Carbon::now($tz),
                ]
            );

            if ($attendance->wasRecentlyCreated) {
                $this->attendanceToday = $attendance->fresh();
                DB::commit();
                session()->flash('success', 'تم تسجيل الحضور بنجاح.');
                return;
            }

            if ($attendance->check_in) {
                DB::rollBack();
                session()->flash('error', 'تم تسجيل حضورك بالفعل اليوم.');
                return;
            }

            $attendance->update(['check_in' => Carbon::now($tz)]);
            $this->attendanceToday = $attendance->fresh();

            DB::commit();
            session()->flash('success', 'تم تسجيل الحضور بنجاح.');
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('error', 'حدث خطأ غير متوقع أثناء تسجيل الحضور.');
        }
    }

    public function checkOut()
    {
        $tz = config('app.timezone', 'Africa/Cairo');

        if (!$this->attendanceToday) {
            session()->flash('error', 'لم يتم تسجيل حضور اليوم.');
            return;
        }

        if ($this->attendanceToday->check_out) {
            session()->flash('error', 'تم تسجيل الانصراف بالفعل.');
            return;
        }

        $checkIn  = Carbon::parse($this->attendanceToday->check_in, $tz);
        $checkOut = Carbon::now($tz);

        if ($checkOut->lessThanOrEqualTo($checkIn)) {
            session()->flash('error', 'وقت الانصراف يجب أن يكون بعد وقت الحضور.');
            return;
        }

        $totalMinutes = $checkIn->diffInMinutes($checkOut);
        $hours   = intdiv($totalMinutes, 60);
        $minutes = $totalMinutes % 60;

        $formattedHours = $hours . ' ساعة ' . $minutes . ' دقيقة';

        try {
            $this->attendanceToday->update([
                'check_out' => $checkOut,
                'hours'     => $formattedHours,
            ]);

            $this->attendanceToday = $this->attendanceToday->fresh();

            session()->flash('success', 'تم تسجيل الانصراف بنجاح.');
        } catch (\Throwable $e) {
            session()->flash('error', 'حدث خطأ غير متوقع أثناء تسجيل الانصراف.');
        }
    }

    public function render()
    {
        return view('livewire.attendance.check', [
            'attendanceToday' => $this->attendanceToday, // ✅ حل مشكلة undefined
        ]);
    }
}
