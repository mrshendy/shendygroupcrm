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
    /** قوائم للاختيارات (اتركها Collections) */
    public $employees = [];
    public $shifts    = [];

    /** اختيار الموظف (لو فاضي هنستخدم موظف المستخدم الحالي) */
    public $employee_id = null;

    /** دعم تعديل طلب موجود */
    public $leave_id = null;

    /** الحقول الأساسية */
    public $leave_type = 'annual';
    public $start_date;
    public $end_date;
    public $reason;

    /** Aliases متوافقة مع Blade الحالي */
    public $leave_employee_id = null;  // بدل employee_id
    public $leave_shift_id    = null;  // اختيار الشيفت للطلب
    public $leave_start_date  = null;  // بدل start_date
    public $leave_end_date    = null;  // بدل end_date

    protected $rules = [
        'leave_type' => 'required|in:annual,casual,sick,unpaid,other',
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
        'reason'     => 'nullable|string|max:255',
        'leave_employee_id' => 'nullable|exists:employees,id',
        'leave_shift_id'    => 'nullable|exists:shifts,id',
    ];

    protected $messages = [
        'leave_type.required'     => 'نوع الإجازة مطلوب',
        'start_date.required'     => 'تاريخ البداية مطلوب',
        'end_date.required'       => 'تاريخ النهاية مطلوب',
        'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون مساويًا أو بعد تاريخ البداية',
    ];

    public function mount($leave_id = null)
    {
        $this->employees = Employee::select('id','full_name')->orderBy('full_name')->get();
        $this->shifts    = Shift::select('id','name')->orderBy('name')->get();

        $this->employee_id       = Auth::user()->employee_id ?? null;
        $this->leave_employee_id = $this->employee_id;

        if ($leave_id) {
            $this->loadLeave($leave_id);
        }
    }

    /** مزامنة اختيار الموظف من الـBlade مع المتغيّر الأساسي */
    public function updatedLeaveEmployeeId($val)
    {
        $this->employee_id = $val;
        $emp = $val ? Employee::select('shift_id')->find($val) : null;
        $this->leave_shift_id = $emp?->shift_id;
    }

    /** تحميل طلب موجود للتعديل */
    public function loadLeave($id)
    {
        $leave = Leave::findOrFail($id);

        $currentEmp = Auth::user()->employee_id;
        if ($currentEmp && $leave->employee_id !== $currentEmp) {
            abort(403, 'غير مصرح لك بتعديل هذا الطلب');
        }

        $this->leave_id           = $leave->id;
        $this->employee_id        = $leave->employee_id;
        $this->leave_employee_id  = $leave->employee_id;
        $this->leave_type         = $leave->leave_type;
        $this->start_date         = optional($leave->start_date)->format('Y-m-d');
        $this->end_date           = optional($leave->end_date)->format('Y-m-d');
        $this->reason             = $leave->reason;
        $this->leave_shift_id     = $leave->shift_id;

        $this->leave_start_date   = $this->start_date;
        $this->leave_end_date     = $this->end_date;
    }

    /** لقب للـBlade */
    public function saveLeave() { return $this->save(); }

    public function save()
    {
        // مزامنة الـaliases
        if ($this->leave_start_date !== null) $this->start_date = $this->leave_start_date;
        if ($this->leave_end_date   !== null) $this->end_date   = $this->leave_end_date;

        $this->validate();

        // الموظف: من الاختيار أو من الجلسة
        $employeeId = $this->leave_employee_id ?: ($this->employee_id ?: (Auth::user()->employee_id ?? null));
        if (!$employeeId) {
            session()->flash('error', 'لا يمكن تحديد الموظف. تأكد من تسجيل الدخول أو اختيار موظف.');
            return;
        }
        $employee = Employee::findOrFail($employeeId);

        // سنة الطلب من تاريخ البداية
        $year = (int) Carbon::parse($this->start_date)->year;

        // رصيد السنة
        $balance = LeaveBalance::where('employee_id', $employeeId)->where('year', $year)->first();
        if (!$balance) {
            session()->flash('error', 'لا يوجد رصيد إجازات لهذا الموظف في هذه السنة');
            return;
        }

        // الشيفت: من employee.shift_id أو Pivot قديم، ويمكن override من اختيار المستخدم
        $shiftId = $employee->shift_id;
        if (!$shiftId) {
            $pivot = DB::table('employee_shift')->where('employee_id', $employeeId)->first();
            $shiftId = $pivot->shift_id ?? null;
        }
        if ($this->leave_shift_id) {
            $shiftId = $this->leave_shift_id;
        }

        $shift = $shiftId ? Shift::find($shiftId) : null;
        $leaveAllowance = $shift && !is_null($shift->leave_allowance) ? (int)$shift->leave_allowance : 4;

        // أيام الطلب الجديد (شامل الطرفين)
        $newDays = Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)) + 1;

        // منع التداخل
        $overlap = Leave::where('employee_id', $employeeId)
            ->whereNotIn('status', ['rejected','cancelled'])
            ->when($this->leave_id, fn($q) => $q->where('id','!=',$this->leave_id))
            ->where(function($q){
                $q->whereBetween('start_date', [$this->start_date, $this->end_date])
                  ->orWhereBetween('end_date',   [$this->start_date, $this->end_date])
                  ->orWhere(function($qq){
                      $qq->where('start_date','<=',$this->start_date)
                         ->where('end_date','>=',$this->end_date);
                  });
            })->exists();

        if ($overlap) {
            $this->addError('start_date', 'هناك طلب إجازة متداخل لنفس الموظف.');
            return;
        }

        // لو تعديل: رجّع خصم الطلب القديم
        if ($this->leave_id) {
            $old = Leave::findOrFail($this->leave_id);
            $oldDays = Carbon::parse($old->start_date)->diffInDays(Carbon::parse($old->end_date)) + 1;

            $balance->used_days      = max(0, (int)$balance->used_days - $oldDays);
            $balance->remaining_days = (int)$balance->remaining_days + $oldDays;

            if ($old->leave_type === 'casual') {
                $balance->casual_days = (int)$balance->casual_days + $oldDays;
            } else {
                $balance->annual_days = (int)$balance->annual_days + $oldDays;
            }
            $balance->save();
        }

        // تحقق من حد الشيفت
        if ($newDays > $leaveAllowance) {
            session()->flash('error', "عدد الأيام المطلوبة أكبر من المسموح به (المتاح: {$leaveAllowance} يوم)");
            return;
        }

        // تحقق من الرصيد حسب النوع
        if ($this->leave_type === 'casual') {
            if ($newDays > (int)$balance->casual_days) {
                session()->flash('error', "رصيد الإجازات العارضة غير كافٍ (المتاح: {$balance->casual_days} يوم)");
                return;
            }
        } else {
            if ($newDays > (int)$balance->annual_days) {
                session()->flash('error', "رصيد الإجازات السنوية غير كافٍ (المتاح: {$balance->annual_days} يوم)");
                return;
            }
        }

        // تحقق من الرصيد الإجمالي
        if ($newDays > (int)$balance->remaining_days) {
            session()->flash('error', "الرصيد الإجمالي غير كافٍ (المتبقي: {$balance->remaining_days} يوم)");
            return;
        }

        // خصم الرصيد
        if ($this->leave_type === 'casual') {
            $balance->casual_days = (int)$balance->casual_days - $newDays;
        } else {
            $balance->annual_days = (int)$balance->annual_days - $newDays;
        }
        $balance->used_days      = (int)$balance->used_days + $newDays;
        $balance->remaining_days = max(0, (int)$balance->remaining_days - $newDays);
        $balance->save();

        // إنشاء/تحديث الطلب
        $payload = [
            'employee_id' => $employeeId,
            'shift_id'    => $shift?->id,
            'leave_type'  => $this->leave_type,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'reason'      => $this->reason,
            'status'      => 'pending',
        ];

        if ($this->leave_id) {
            Leave::findOrFail($this->leave_id)->update($payload);
            session()->flash('success', 'تم تحديث طلب الإجازة بنجاح');
        } else {
            Leave::create($payload);
            session()->flash('success', 'تم تقديم طلب الإجازة بنجاح');
        }

        // تفريغ الحقول (واترك leave_id لو عايز تفضل في وضع التعديل)
        $this->reset(['leave_type','start_date','end_date','reason','leave_shift_id','leave_start_date','leave_end_date']);
        // $this->leave_id = null;
    }

    public function render()
    {
        return view('livewire.employees.leaves.create');
    }
}
