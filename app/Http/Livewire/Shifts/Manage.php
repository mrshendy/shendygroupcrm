<?php

namespace App\Http\Livewire\Shifts;

use Livewire\Component;
use App\Models\Shift;
use App\Models\Employee;

class Manage extends Component
{
    public $shift_id;
    public $name;
    public $days = [];
    public $start_time;
    public $end_time;
    public $leave_allowance;
    public $employee_ids = [];   // الموظفين المختارين
    public $allEmployees = [];   // كل الموظفين من الداتابيس

    public $shifts = [];

    public function mount()
    {
        $this->loadShifts();
        $this->allEmployees = Employee::select('id','full_name')->get(); // جلب كل الموظفين
    }

    public function loadShifts()
    {
        $this->shifts = Shift::with('employees')->get();
    }

    public function save()
    {
        $data = $this->validate([
            'name'           => 'required|string|max:255',
            'days'           => 'required|array|min:1',
            'start_time'     => 'required',
            'end_time'       => 'required',
            'leave_allowance'=> 'required|integer|min:0',
            'employee_ids'   => 'array',
        ], [
            'name.required' => 'اسم الشيفت مطلوب',
            'days.required' => 'اختر يوم واحد على الأقل',
            'start_time.required' => 'وقت البداية مطلوب',
            'end_time.required'   => 'وقت النهاية مطلوب',
            'leave_allowance.required' => 'مدة الإجازة مطلوبة',
        ]);

        if ($this->shift_id) {
            $shift = Shift::findOrFail($this->shift_id);
            $shift->update($data);
        } else {
            $shift = Shift::create($data);
        }

        // ربط الموظفين بالشيفت
        $shift->employees()->sync($this->employee_ids);

        $this->resetForm();
        $this->loadShifts();

        session()->flash('success', 'تم حفظ الشيفت بنجاح');
    }

    public function edit($id)
    {
        $shift = Shift::with('employees')->findOrFail($id);

        $this->shift_id = $shift->id;
        $this->name = $shift->name;
        $this->days = $shift->days;
        $this->start_time = $shift->start_time;
        $this->end_time = $shift->end_time;
        $this->leave_allowance = $shift->leave_allowance;
        $this->employee_ids = $shift->employees->pluck('id')->toArray();
    }

    public function delete($id)
    {
        $shift = Shift::findOrFail($id);
        $shift->employees()->detach(); // فك الارتباط بالموظفين
        $shift->delete();

        $this->loadShifts();
        session()->flash('success', 'تم حذف الشيفت بنجاح');
    }

    public function resetForm()
    {
        $this->shift_id = null;
        $this->name = '';
        $this->days = [];
        $this->start_time = '';
        $this->end_time = '';
        $this->leave_allowance = '';
        $this->employee_ids = [];
    }

    public function render()
    {
        return view('livewire.shifts.manage');
    }
}
