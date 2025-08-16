<?php

namespace App\Http\Livewire\Employees\Leaves;

use Livewire\Component;
use App\Models\Leave;
use App\Models\LeaveBalance;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

        // 1️⃣ جلب رصيد الموظف
        $balance = LeaveBalance::where('employee_id', $employeeId)
            ->where('year', now()->year)
            ->first();

        if (!$balance) {
            session()->flash('error', 'لا يوجد رصيد إجازات لهذا الموظف');
            return;
        }

        // 2️⃣ جلب الشيفت الخاص بالموظف (لو مرتبط بشيفت)
        $shift = Shift::where('id', Auth::user()->shift_id ?? null)->first();

        // 3️⃣ حساب عدد الأيام المطلوبة
        $daysRequested = Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)) + 1;

        // 4️⃣ التحقق من رصيد الشيفت (لو محدد)
        if ($shift && $daysRequested > $shift->leave_allowance) {
            session()->flash('error', 'عدد الأيام المطلوبة أكبر من الحد المسموح به في الشيفت (المتاح: '.$shift->leave_allowance.' يوم)');
            return;
        }

        // 5️⃣ التحقق من رصيد الإجازات حسب النوع
        if ($this->leave_type === 'casual') {
            if ($daysRequested > $balance->casual_days) {
                session()->flash('error', 'رصيد الإجازات العارضة غير كافٍ (المتاح: '.$balance->casual_days.' يوم)');
                return;
            }
            $balance->casual_days -= $daysRequested;
        } else {
            if ($daysRequested > $balance->annual_days) {
                session()->flash('error', 'رصيد الإجازات السنوية غير كافٍ (المتاح: '.$balance->annual_days.' يوم)');
                return;
            }
            $balance->annual_days -= $daysRequested;
        }

        $balance->save();

        // 6️⃣ إنشاء طلب الإجازة
        Leave::create([
            'employee_id' => $employeeId,
            'leave_type'  => $this->leave_type,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'reason'      => $this->reason,
            'status'      => 'pending',
        ]);

        session()->flash('success', 'تم تقديم طلب الإجازة بنجاح');
        $this->reset(['leave_type','start_date','end_date','reason']);
    }

    public function render()
    {
        return view('livewire.employees.leaves.create');
    }
}
