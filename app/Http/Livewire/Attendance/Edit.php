<?php

namespace App\Http\Livewire\Attendance;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\Employee;

class Edit extends Component
{
    public $attendance_id;
    public $employee_id;
    public $check_in;
    public $check_out;
    public $hours;
    public $date;

    public $employees = []; // قائمة الموظفين

    protected $rules = [
        'employee_id' => 'required|exists:employees,id',
        'check_in'    => 'required|date_format:H:i',
        'check_out'   => 'nullable|date_format:H:i|after:check_in',
        'hours'       => 'nullable|numeric|min:0',
        'date'        => 'required|date',
    ];

    protected $messages = [
        'employee_id.required' => 'يجب اختيار الموظف',
        'employee_id.exists'   => 'الموظف غير موجود',
        'check_in.required'    => 'وقت الحضور مطلوب',
        'check_in.date_format' => 'صيغة وقت الحضور غير صحيحة (HH:MM)',
        'check_out.after'      => 'وقت الانصراف يجب أن يكون بعد وقت الحضور',
        'check_out.date_format'=> 'صيغة وقت الانصراف غير صحيحة (HH:MM)',
        'date.required'        => 'التاريخ مطلوب',
        'date.date'            => 'صيغة التاريخ غير صحيحة',
        'hours.numeric'        => 'عدد الساعات يجب أن يكون رقم',
    ];

    public function mount($id)
    {
        $attendance = Attendance::findOrFail($id);

        $this->attendance_id = $attendance->id;
        $this->employee_id   = $attendance->employee_id;
        $this->check_in      = $attendance->check_in ? date('H:i', strtotime($attendance->check_in)) : null;
        $this->check_out     = $attendance->check_out ? date('H:i', strtotime($attendance->check_out)) : null;
        $this->hours         = $attendance->hours;
        $this->date          = $attendance->date ? date('Y-m-d', strtotime($attendance->date)) : null;

        $this->employees     = Employee::select('id', 'full_name')->orderBy('full_name')->get();
    }

    public function save()
    {
        $data = $this->validate();

        // دمج التاريخ مع وقت الحضور والانصراف
        $data['check_in']  = $this->date . ' ' . $this->check_in . ':00';
        $data['check_out'] = $this->check_out ? $this->date . ' ' . $this->check_out . ':00' : null;

        // حساب الساعات تلقائيًا
        if ($this->check_in && $this->check_out) {
            $in  = strtotime($data['check_in']);
            $out = strtotime($data['check_out']);
            $data['hours'] = round(($out - $in) / 3600, 2);
        }

        $attendance = Attendance::findOrFail($this->attendance_id);
        $attendance->update($data);

        session()->flash('success', 'تم تعديل بيانات الحضور والانصراف بنجاح ✅');
        return redirect()->route('attendance.manage');
    }

    public function render()
    {
        return view('livewire.attendance.edit')
            ->layout('layouts.master');
    }
}
