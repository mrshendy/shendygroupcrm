<?php

namespace App\Http\Livewire\Employees\Leaves;

use Livewire\Component;
use App\Models\Leave;
use App\Models\Employee;

class Manage extends Component
{
    public $leave_id;
    public $employee_id;
    public $type = 'annual';
    public $start_date;
    public $end_date;
    public $days_count;
    public $reason;
    public $status = 'pending';

    public $employees = [];
    public $leaves = [];

    protected $rules = [
        'employee_id' => 'required|exists:employees,id',
        'type'        => 'required|in:annual,sick,emergency',
        'start_date'  => 'required|date',
        'end_date'    => 'required|date|after_or_equal:start_date',
        'days_count'  => 'required|integer|min:1',
        'status'      => 'required|in:pending,approved,rejected',
        'reason'      => 'nullable|string|max:500',
    ];

    protected $messages = [
        'employee_id.required' => 'الرجاء اختيار الموظف.',
        'employee_id.exists'   => 'الموظف غير موجود.',
        'type.required'        => 'الرجاء اختيار نوع الإجازة.',
        'start_date.required'  => 'الرجاء تحديد تاريخ البداية.',
        'end_date.required'    => 'الرجاء تحديد تاريخ النهاية.',
        'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون مساوي أو بعد تاريخ البداية.',
        'days_count.required'  => 'الرجاء تحديد عدد الأيام.',
    ];

    public function mount()
    {
        $this->employees = Employee::orderBy('full_name')->get();
        $this->loadLeaves();
    }

    public function loadLeaves()
    {
        $this->leaves = Leave::with('employee')->latest()->get();
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->leave_id) {
            Leave::findOrFail($this->leave_id)->update($data);
            session()->flash('success', 'تم تعديل الإجازة بنجاح.');
        } else {
            Leave::create($data);
            session()->flash('success', 'تم إضافة الإجازة بنجاح.');
        }

        $this->resetForm();
        $this->loadLeaves();
    }

    public function edit($id)
    {
        $leave = Leave::findOrFail($id);

        $this->leave_id    = $leave->id;
        $this->employee_id = $leave->employee_id;
        $this->type        = $leave->type;
        $this->start_date  = $leave->start_date;
        $this->end_date    = $leave->end_date;
        $this->days_count  = $leave->days_count;
        $this->reason      = $leave->reason;
        $this->status      = $leave->status;
    }

    public function delete($id)
    {
        Leave::findOrFail($id)->delete();
        session()->flash('success', 'تم حذف الإجازة بنجاح.');
        $this->loadLeaves();
    }

    public function resetForm()
    {
        $this->leave_id = null;
        $this->employee_id = null;
        $this->type = 'annual';
        $this->start_date = null;
        $this->end_date = null;
        $this->days_count = null;
        $this->reason = null;
        $this->status = 'pending';
    }

    public function render()
    {
        return view('livewire.employees.leaves.manage');
    }
}
