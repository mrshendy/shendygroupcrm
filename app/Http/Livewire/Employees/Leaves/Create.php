<?php

namespace App\Http\Livewire\Employees\Leaves;

use Livewire\Component;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $leave_type = 'annual';
    public $start_date;
    public $end_date;
    public $reason;

    protected $rules = [
        'leave_type' => 'required|in:annual,sick,unpaid,other',
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
        'reason'     => 'nullable|string|max:255',
    ];

    protected $messages = [
        'leave_type.required' => 'نوع الإجازة مطلوب',
        'start_date.required' => 'تاريخ البداية مطلوب',
        'end_date.required'   => 'تاريخ النهاية مطلوب',
        'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي البداية',
    ];

    public function save()
    {
        $this->validate();

        Leave::create([
            'employee_id' => Auth::user()->employee_id, // كل موظف ليه حساب
            'leave_type'  => $this->leave_type,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'reason'      => $this->reason,
            'status'      => 'pending',
        ]);

        session()->flash('success', 'تم تقديم طلب الإجازة بنجاح، بانتظار الموافقة.');

        $this->reset(['leave_type', 'start_date', 'end_date', 'reason']);
    }

    public function render()
    {
        return view('livewire.employees.leaves.create');
    }
}
