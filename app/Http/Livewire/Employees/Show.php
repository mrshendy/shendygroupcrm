<?php

namespace App\Http\Livewire\Employees;

use Livewire\Component;
use App\Models\Employee;

class Show extends Component
{
    public $employee;

    public function mount($id)
    {
        $this->employee = Employee::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.employees.show');
    }
}
