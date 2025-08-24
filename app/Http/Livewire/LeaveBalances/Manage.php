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
    public $shift_id = null;

    // --------- نموذج الإجازة ----------
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
        // رصيد الإجازات
        'employee_id' => 'required|exists:employees,id',
        'year'        => 'required|integer|min:2000|max:2100',
        'total_days'  => 'required|integer|min:1|max:365',
        'used_days'   => 'required|integer|min:0|lte:total_days',
        'annual_days' => 'required|integer|min:0',
        'casual_days' => 'required|integer|min:0',
        'shift_id'    => 'required|exists:shifts,id',

        // الإجازة
        'leave_employee_id' => 'required|exists:employees,id',
        'leave_shift_id'    => 'required|exists:shifts,id',
        'leave_start_date'  => 'required|date',
        'leave_end_date'    => 'required|date|after_or_equal:leave_start_date',
        'leave_type'        => 'required|in:annual,casual,sick,unpaid,other',
        'leave_status'      => 'required|in:pending,approved,rejected,cancelled',
        'leave_reason'      => 'nullable|string|max:1000',
    ];

    protected $messages = [
        // رصيد
        'employee_id.required' => 'يجب اختيار الموظف.',
        'employee_id.exists'   => 'الموظف المحدد غير موجود.',
        'year.required'        => 'يجب إدخال السنة.',
        'year.integer'         => 'السنة يجب أن تكون رقمًا صحيحًا.',
        'total_days.required'  => 'يجب إدخال إجمالي أيام الإجازات.',
        'total_days.min'       => 'إجمالي الأيام يجب أن يكون على الأقل يوم واحد.',
        'used_days.required'   => 'يجب إدخال عدد الأيام المستخدمة.',
        'used_days.lte'        => 'الأيام المستخدمة لا يمكن أن تتجاوز الإجمالي.',
        'annual_days.required' => 'يجب إدخال عدد أيام الإجازة السنوية.',
        'casual_days.required' => 'يجب إدخال عدد أيام الإجازة العارضة.',
        'shift_id.required'    => 'يجب اختيار الشيفت.',
        'shift_id.exists'      => 'الشيفت المحدد غير صحيح.',

        // إجازة
        'leave_employee_id.required' => 'يجب اختيار الموظف للإجازة.',
        'leave_employee_id.exists'   => 'الموظف المحدد غير صحيح.',
        'leave_shift_id.required'    => 'يجب اختيار شيفت الموظف.',
        'leave_start_date.required'  => 'يجب إدخال تاريخ بداية الإجازة.',
        'leave_end_date.required'    => 'يجب إدخال تاريخ نهاية الإجازة.',
        'leave_end_date.after_or_equal' => 'تاريخ نهاية الإجازة يجب أن يكون بعد أو يساوي البداية.',
        'leave_type.required'        => 'يجب اختيار نوع الإجازة.',
        'leave_status.required'      => 'يجب اختيار حالة الإجازة.',
        'leave_reason.max'           => 'سبب الإجازة يجب ألا يتجاوز 1000 حرف.',
    ];

    public function mount()
    {
        $this->employees = Employee::select('id','full_name','shift_id')->orderBy('full_name')->get();
        $this->shifts    = Shift::select('id','name')->orderBy('name')->get()->toArray();
        $this->year      = now()->year;
        $this->calcRemaining();
    }

    
}
