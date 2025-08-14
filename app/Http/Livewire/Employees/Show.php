<?php

namespace App\Http\Livewire\Employees;

use Livewire\Component;
use App\Models\Employee;

class Show extends Component
{
    public $employee;

    public function mount($id)
    {
        $this->employee = Employee::find($id);

        if (!$this->employee) {
            session()->flash('error', 'الموظف غير موجود');
            return redirect()->route('employees.index');
        }
    }

    public function render()
    {
        return view('livewire.employees.show', [
            'employee' => $this->employee
        ]);
    }
}
