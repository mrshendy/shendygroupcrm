<?php

namespace App\Http\Livewire\Employees\Leaves;

use Livewire\Component;
use App\Models\Leave;
use App\Models\LeaveBalance;
use App\Models\Shift;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $leave_type = 'annual';
    public $start_date;
    public $end_date;
    public $reason;

    protected $rules = [
        'leave_type' => 'required|in:annual,casual,sick,unpaid,other',
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
        'reason'     => 'nullable|string|max:255',
    ];

    protected $messages = [
        'leave_type.required' => 'نوع الإجازة مطلوب',
        'start_date.required' => 'تاريخ البداية مطلوب',
        'end_date.required'   => 'تاريخ النهاية مطلوب',
        'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون مساويًا أو بعد تاريخ البداية',
    ];

    public function save()
    {
        $this->validate();

        $employeeId = Auth::user()->employee_id;
        $employee   = Employee::find($employeeId);

        // ✅ جلب رصيد الموظف
        $balance = LeaveBalance::where('employee_id', $employeeId)
            ->where('year', now()->year)
            ->first();

        if (!$balance) {
            session()->flash('error', 'لا يوجد رصيد إجازات لهذا الموظف');
            return;
        }

        // ✅ جلب الشيفت المرتبط بالموظف
        $employeeShift = DB::table('employee_shift')
            ->where('employee_id', $employeeId)
            ->first();

        $shift = null;
        $leaveAllowance = 4; // 👈 الافتراضي دايمًا 4 لو مفيش أي حاجة

        if ($employeeShift) {
            $shift = Shift::find($employeeShift->shift_id);

            // 👇 لو الموظف عنده قيمة خاصة نستخدمها، غير كده نرجع للي في الشيفت أو الافتراضي
            if (!is_null($employeeShift->custom_leave_allowance)) {
                $leaveAllowance = $employeeShift->custom_leave_allowance;
            } elseif ($shift) {
                $leaveAllowance = $shift->leave_allowance ?? 4;
            }
        }

        // ✅ حساب عدد الأيام المطلوبة
        $daysRequested = Carbon::parse($this->start_date)
            ->diffInDays(Carbon::parse($this->end_date)) + 1;

        // ✅ تحقق من الحد الأقصى
        if ($daysRequested > $leaveAllowance) {
            session()->flash('error', "عدد الأيام المطلوبة أكبر من المسموح به (المتاح: {$leaveAllowance} يوم)");
            return;
        }

        // ✅ تحقق من رصيد الإجازات حسب النوع
        if ($this->leave_type === 'casual') {
            if ($daysRequested > $balance->casual_days) {
                session()->flash('error', "رصيد الإجازات العارضة غير كافٍ (المتاح: {$balance->casual_days} يوم)");
                return;
            }
            $balance->casual_days -= $daysRequested;
        } else {
            if ($daysRequested > $balance->annual_days) {
                session()->flash('error', "رصيد الإجازات السنوية غير كافٍ (المتاح: {$balance->annual_days} يوم)");
                return;
            }
            $balance->annual_days -= $daysRequested;
        }

        // ✅ تحديث إجمالي الرصيد
        $balance->used_days      += $daysRequested;
        $balance->remaining_days -= $daysRequested;
        $balance->save();

        // ✅ إنشاء طلب الإجازة
        Leave::create([
            'employee_id' => $employeeId,
            'shift_id'    => $shift?->id,
            'leave_type'  => $this->leave_type,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'reason'      => $this->reason,
            'status'      => 'pending',
        ]);

        session()->flash('success', 'تم تقديم طلب الإجازة بنجاح');
        $this->reset(['leave_type', 'start_date', 'end_date', 'reason']);
    }

    public function render()
    {
        return view('livewire.employees.leaves.create');
    }
}
