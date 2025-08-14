<?php

namespace App\Http\Livewire\Attendance;

use Livewire\Component;
use App\Models\Attendance;

class Edit extends Component
{
    public $attendance_id;
    public $employee_id;
    public $check_in;
    public $check_out;
    public $hours;
    public $date;

    protected $rules = [
        'employee_id' => 'required|exists:employees,id',
        'check_in'    => 'required|date_format:H:i',
        'check_out'   => 'nullable|date_format:H:i|after:check_in',
        'hours'       => 'nullable|numeric|min:0',
        'date'        => 'required|date',
    ];

    protected $messages = [
        'employee_id.required' => 'يجب اختيار الموظف',
        'check_in.required'    => 'وقت الحضور مطلوب',
        'check_out.after'      => 'وقت الانصراف يجب أن يكون بعد وقت الحضور',
        'date.required'        => 'التاريخ مطلوب',
    ];

    public function mount($id)
    {
        $attendance = Attendance::findOrFail($id);

        $this->attendance_id = $attendance->id;
        $this->employee_id   = $attendance->employee_id;
        $this->check_in      = $attendance->check_in;
        $this->check_out     = $attendance->check_out;
        $this->hours         = $attendance->hours;
        $this->date          = $attendance->date;
    }

    public function save()
{
    $data = $this->validate();

    // دمج التاريخ مع وقت الحضور والانصراف
    $data['check_in']  = $this->date . ' ' . $this->check_in . ':00';
    $data['check_out'] = $this->check_out ? $this->date . ' ' . $this->check_out . ':00' : null;

    $attendance = Attendance::findOrFail($this->attendance_id);
    $attendance->update($data);

    session()->flash('success', 'تم تعديل بيانات الحضور والانصراف بنجاح');
    return redirect()->route('attendance.manage');
}


    public function render()
    {
        return view('livewire.attendance.edit');
    }
}
