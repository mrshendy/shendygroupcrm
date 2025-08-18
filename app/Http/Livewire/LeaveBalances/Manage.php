<?php

namespace App\Http\Livewire\LeaveBalances;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LeaveBalance;
use App\Models\Leave;
use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Support\Carbon;

class Manage extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // --------- نموذج رصيد الإجازات ----------
    public $leave_balance_id = null;
    public $employee_id = null;
    public $year;
    public $total_days = 21;
    public $used_days = 0;
    public $remaining_days = 21;
    public $annual_days = 15;
    public $casual_days = 6;
    public $shift_id = null;      // اختيار شيفت الموظف

    // --------- نموذج الإجازة (إنشاء/تعديل) ----------
    public $leave_id = null;
    public $leave_employee_id = null;
    public $leave_shift_id = null;
    public $leave_start_date = null;
    public $leave_end_date = null;
    public $leave_type = 'annual';
    public $leave_status = 'approved';
    public $leave_reason = null;

    // واجهة الجدول
    public $employees = [];
    public $shifts = [];
    public $search = '';
    public $perPage = 10;
    public $sortField = 'year';
    public $sortDirection = 'desc';

    protected $rules = [
        // رصيد
        'employee_id' => 'required|exists:employees,id',
        'year'        => 'required|integer|min:2000|max:2100',
        'total_days'  => 'required|integer|min:0|max:365',
        'used_days'   => 'required|integer|min:0|lte:total_days',
        'annual_days' => 'required|integer|min:0',
        'casual_days' => 'required|integer|min:0',
        'shift_id'    => 'nullable|exists:shifts,id',

        // إجازة
        'leave_employee_id' => 'nullable|exists:employees,id',
        'leave_shift_id'    => 'nullable|exists:shifts,id',
        'leave_start_date'  => 'nullable|date',
        'leave_end_date'    => 'nullable|date|after_or_equal:leave_start_date',
        'leave_type'        => 'nullable|in:annual,casual,sick,unpaid,other',
        'leave_status'      => 'nullable|in:pending,approved,rejected,cancelled',
        'leave_reason'      => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'employee_id.required'          => 'اختر الموظف.',
        'year.required'                 => 'أدخل السنة.',
        'total_days.required'           => 'أدخل إجمالي الأيام.',
        'used_days.lte'                 => 'الأيام المستخدمة يجب ألا تتجاوز الإجمالي.',
        'leave_start_date.required'     => 'اختر تاريخ البداية.',
        'leave_end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي البداية.',
    ];

    public function mount()
    {
        $this->employees = Employee::select('id','full_name','shift_id')->orderBy('full_name')->get();
        $this->shifts    = Shift::select('id','name')->orderBy('name')->get()->toArray();
        $this->year      = now()->year;
        $this->calcRemaining();
    }

    // ---------- تفاعلات بسيطة ----------
    public function updated($field)
    {
        if (in_array($field, ['total_days','used_days'])) $this->calcRemaining();
        if ($field === 'search') $this->resetPage();
    }

    // لو اخترت موظفًا في رصيد الإجازات.. عيّن شيفته واملأ الإجمالي من الشيفت (لو موجود)
    public function updatedEmployeeId($val)
    {
        $emp = $val ? Employee::select('shift_id')->find($val) : null;
        $this->shift_id = $emp?->shift_id;

        if ($this->shift_id) {
            $allowance = Shift::whereKey($this->shift_id)->value('leave_allowance');
            if ($allowance) {
                $this->total_days = (int) $allowance;
                $this->calcRemaining();
            }
        }
    }

    // لو غيّرت الشيفت يدويًا.. عيّن الإجمالي من الشيفت
    public function updatedShiftId($val)
    {
        if ($val) {
            $allowance = Shift::whereKey($val)->value('leave_allowance');
            if ($allowance) {
                $this->total_days = (int) $allowance;
                $this->calcRemaining();
            }
        }
    }

    // لو اخترت موظفًا في نموذج الإجازة.. عيّن شيفته تلقائيًا
    public function updatedLeaveEmployeeId($val)
    {
        $emp = $val ? Employee::select('shift_id')->find($val) : null;
        $this->leave_shift_id = $emp?->shift_id;
    }

    // ---------- حساب المتبقي ----------
    public function calcRemaining()
    {
        $t = (int)($this->total_days ?? 0);
        $u = (int)($this->used_days ?? 0);
        $this->remaining_days = max(0, $t - $u);
    }

    // ---------- إدارة الرصيد ----------
    public function save()
    {
        $data = $this->validate($this->balanceRules());

        // حدّث شيفت الموظف لو اتحدد
        if ($this->employee_id !== null) {
            Employee::whereKey($this->employee_id)->update(['shift_id' => $this->shift_id]);
        }

        $data['remaining_days'] = max(0, (int)$data['total_days'] - (int)$data['used_days']);

        if ($this->leave_balance_id) {
            LeaveBalance::findOrFail($this->leave_balance_id)->update($data);
        } else {
            LeaveBalance::create($data);
        }

        session()->flash('success', 'تم حفظ رصيد الإجازات.');
        return redirect()->route('leave-balances.manage');
    }

    protected function balanceRules(): array
    {
        return [
            'employee_id' => $this->rules['employee_id'],
            'year'        => $this->rules['year'],
            'total_days'  => $this->rules['total_days'],
            'used_days'   => $this->rules['used_days'],
            'annual_days' => $this->rules['annual_days'],
            'casual_days' => $this->rules['casual_days'],
            'shift_id'    => $this->rules['shift_id'],
        ];
    }

    public function edit($id)
    {
        $lb = LeaveBalance::with('employee')->findOrFail($id);
        $this->leave_balance_id = $lb->id;
        $this->employee_id      = $lb->employee_id;
        $this->year             = $lb->year;
        $this->total_days       = $lb->total_days;
        $this->used_days        = $lb->used_days;
        $this->remaining_days   = $lb->remaining_days;
        $this->annual_days      = $lb->annual_days;
        $this->casual_days      = $lb->casual_days;
        $this->shift_id         = $lb->employee->shift_id ?? null;

        // جهّز نموذج الإجازة لنفس الموظف والسنة
        $this->prefillLeave($lb->id);
    }

    public function delete($id)
    {
        LeaveBalance::findOrFail($id)->delete();
        session()->flash('success', 'تم حذف السجل.');
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->leave_balance_id = null;
        $this->employee_id = null;
        $this->year = now()->year;
        $this->total_days = 21;
        $this->used_days = 0;
        $this->remaining_days = 21;
        $this->annual_days = 15;
        $this->casual_days = 6;
        $this->shift_id = null;
        $this->resetValidation();
    }

    // ---------- الإجازة من شاشة الرصيد ----------
    public function prefillLeave($balanceId)
    {
        $lb = LeaveBalance::findOrFail($balanceId);
        $this->leave_id          = null;
        $this->leave_employee_id = $lb->employee_id;
        $this->leave_shift_id    = optional($lb->employee)->shift_id;
        $today = now()->toDateString();
        $this->leave_start_date  = $today;
        $this->leave_end_date    = $today;
        $this->leave_type        = 'annual';
        $this->leave_status      = 'approved';
        $this->leave_reason      = null;
    }

    public function editLeave($id)
    {
        $l = Leave::findOrFail($id);
        $this->leave_id          = $l->id;
        $this->leave_employee_id = $l->employee_id;
        $this->leave_shift_id    = $l->shift_id;
        $this->leave_type        = $l->leave_type;
        $this->leave_status      = $l->status;
        $this->leave_reason      = $l->reason;
        $this->leave_start_date  = $l->start_date;
        $this->leave_end_date    = $l->end_date;
    }

    public function resetLeaveForm()
    {
        $this->leave_id = null;
        $this->leave_employee_id = null;
        $this->leave_shift_id = null;
        $this->leave_type = 'annual';
        $this->leave_status = 'approved';
        $this->leave_reason = null;
        $this->leave_start_date = null;
        $this->leave_end_date = null;
        $this->resetValidation();
    }

    public function saveLeave()
    {
        $this->validate([
            'leave_employee_id' => 'required|exists:employees,id',
            'leave_shift_id'    => 'nullable|exists:shifts,id',
            'leave_start_date'  => 'required|date',
            'leave_end_date'    => 'required|date|after_or_equal:leave_start_date',
            'leave_type'        => 'required|in:annual,casual,sick,unpaid,other',
            'leave_status'      => 'required|in:pending,approved,rejected,cancelled',
            'leave_reason'      => 'nullable|string|max:1000',
        ], $this->messages);

        $year = (int) Carbon::parse($this->leave_start_date)->year;

        // لو الشيفت غير محدد.. خد شيفت الموظف
        if (!$this->leave_shift_id) {
            $this->leave_shift_id = optional(Employee::select('shift_id')->find($this->leave_employee_id))->shift_id;
        }

        // منع التداخل
        $overlap = Leave::where('employee_id', $this->leave_employee_id)
            ->whereNotIn('status', ['rejected','cancelled'])
            ->when($this->leave_id, fn($q) => $q->where('id', '!=', $this->leave_id))
            ->where(function($q){
                $q->whereBetween('start_date', [$this->leave_start_date, $this->leave_end_date])
                  ->orWhereBetween('end_date',   [$this->leave_start_date, $this->leave_end_date])
                  ->orWhere(function($qq){
                      $qq->where('start_date','<=',$this->leave_start_date)
                         ->where('end_date','>=',$this->leave_end_date);
                  });
            })->exists();

        if ($overlap) {
            $this->addError('leave_start_date', 'هناك طلب إجازة متداخل لنفس الموظف.');
            return;
        }

        // تأكد من وجود/تجهيز رصيد السنة
        $balance = LeaveBalance::firstOrCreate(
            ['employee_id'=>$this->leave_employee_id,'year'=>$year],
            ['total_days' => ($this->leave_shift_id ? (int) (Shift::find($this->leave_shift_id)?->leave_allowance ?? 21) : 21),
             'used_days'=>0,'remaining_days'=>0,'annual_days'=>15,'casual_days'=>6]
        );

        // تحقق من الرصيد عند الاعتماد
        $days = $this->diffDaysInclusive($this->leave_start_date, $this->leave_end_date);
        if ($this->leave_status === 'approved' && $days > (int)$balance->remaining_days) {
            $this->addError('leave_end_date', 'الرصيد لا يكفي لهذه المدة.');
            return;
        }

        $payload = [
            'employee_id' => $this->leave_employee_id,
            'shift_id'    => $this->leave_shift_id,
            'leave_type'  => $this->leave_type,
            'start_date'  => $this->leave_start_date,
            'end_date'    => $this->leave_end_date,
            'reason'      => $this->leave_reason,
            'status'      => $this->leave_status,
        ];

        if ($this->leave_id) {
            Leave::findOrFail($this->leave_id)->update($payload);
            $msg = 'تم تحديث طلب الإجازة.';
        } else {
            Leave::create($payload);
            $msg = 'تم تسجيل الإجازة.';
        }

        $this->recalcBalanceFor($this->leave_employee_id, $year);

        session()->flash('success', $msg);
        return redirect()->route('leave-balances.manage');
    }

    // --------- مساعدات ---------
    protected function recalcBalanceFor($employeeId, $year)
    {
        $approvedLeaves = Leave::where('employee_id',$employeeId)
            ->whereYear('start_date', $year)
            ->where('status','approved')
            ->get();

        $used = 0;
        foreach ($approvedLeaves as $l) {
            $used += $this->diffDaysInclusive($l->start_date, $l->end_date);
        }

        $balance = LeaveBalance::firstOrCreate(
            ['employee_id'=>$employeeId,'year'=>$year],
            ['total_days'=>21,'used_days'=>0,'remaining_days'=>0,'annual_days'=>15,'casual_days'=>6]
        );

        $balance->used_days = (int)$used;
        $balance->remaining_days = max(0, (int)$balance->total_days - (int)$balance->used_days);
        $balance->save();
    }

    private function diffDaysInclusive($start, $end): int
    {
        $s = Carbon::parse($start);
        $e = Carbon::parse($end);
        return $s->diffInDays($e) + 1;
    }

    // --------- الجدول / الفرز ----------
    private function rowsQuery()
    {
        return LeaveBalance::query()
            ->with(['employee:id,full_name,shift_id','employee.shift:id,name'])
            ->when($this->search, function($q){
                $term = '%'.$this->search.'%';
                $q->where(function($sub) use ($term){
                    $sub->whereHas('employee', fn($e)=>$e->where('full_name','like',$term))
                        ->orWhere('year','like',$term);
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery()->paginate($this->perPage);
    }

    public function getStatsProperty()
    {
        $base = $this->rowsQuery()->get();
        return [
            'count'      => $base->count(),
            'total_days' => (int)$base->sum('total_days'),
            'used_days'  => (int)$base->sum('used_days'),
            'remaining'  => (int)$base->sum('remaining_days'),
        ];
    }

    public function render()
    {
        return view('livewire.leave-balances.manage', [
            'items' => $this->rows,
            'stats' => $this->stats,
        ]);
    }
}
