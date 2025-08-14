<?php

namespace App\Http\Livewire\Employees\Leaves;

use Livewire\Component;
use App\Models\Leave;
use App\Models\Employee;

class Index extends Component
{
    public $leaves;
    public $employee_id, $type, $start_date, $end_date, $days_count, $reason, $status;
    public $leave_id;

    protected $rules = [
        'employee_id' => 'required|exists:employees,id',
        'type' => 'required|in:annual,sick,emergency',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'days_count' => 'required|numeric',
        'reason' => 'nullable|string',
        'status' => 'required|in:pending,approved,rejected',
    ];

    public function mount()
    {
        $this->loadLeaves();
    }

    public function loadLeaves()
    {
        $this->leaves = Leave::with('employee')->latest()->get();
    }

    public function save()
    {
        $this->validate();

        Leave::updateOrCreate(
            ['id' => $this->leave_id],
            [
                'employee_id' => $this->employee_id,
                'type' => $this->type,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'days_count' => $this->days_count,
                'reason' => $this->reason,
                'status' => $this->status,
            ]
        );

        $this->resetInput();
        $this->loadLeaves();
        session()->flash('success', 'تم الحفظ بنجاح');
    }

    public function edit($id)
    {
        $leave = Leave::findOrFail($id);
        $this->leave_id = $leave->id;
        $this->employee_id = $leave->employee_id;
        $this->type = $leave->type;
        $this->start_date = $leave->start_date;
        $this->end_date = $leave->end_date;
        $this->days_count = $leave->days_count;
        $this->reason = $leave->reason;
        $this->status = $leave->status;
    }

    public function delete($id)
    {
        Leave::findOrFail($id)->delete();
        $this->loadLeaves();
    }

    public function resetInput()
    {
        $this->leave_id = null;
        $this->employee_id = '';
        $this->type = 'annual';
        $this->start_date = '';
        $this->end_date = '';
        $this->days_count = '';
        $this->reason = '';
        $this->status = 'pending';
    }

    public function render()
    {
        return view('livewire.employees.leaves.index', [
            'employees' => Employee::all()
        ]);
    }
}
