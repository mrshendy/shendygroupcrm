<?php

namespace App\Http\Livewire\Employees\Salaries;

use Livewire\Component;
use App\Models\Salary;
use App\Models\Employee;

class Edit extends Component
{
    public $salaryId;
    public $employee_id, $month, $basic_salary, $allowances, $deductions, $net_salary, $status, $notes;
    public $employees = []; // ✅ عشان تستخدمه في الواجهة

    public function mount($salaryId)
    {
        $this->employees = Employee::select('id', 'full_name')->get(); // جلب الموظفين

        $salary = Salary::findOrFail($salaryId);
        $this->salaryId     = $salary->id;
        $this->employee_id  = $salary->employee_id;
        $this->month        = $salary->month;
        $this->basic_salary = $salary->basic_salary;
        $this->allowances   = $salary->allowances;
        $this->deductions   = $salary->deductions;
        $this->net_salary   = $salary->net_salary;
        $this->status       = $salary->status;
        $this->notes        = $salary->notes;
    }

    public function update()
    {
        $this->validate([
            'employee_id'  => 'required|exists:employees,id',
            'month'        => 'required|date',
            'basic_salary' => 'required|numeric',
        ]);

        $salary = Salary::findOrFail($this->salaryId);

        $salary->update([
            'employee_id'  => $this->employee_id,
            'month'        => $this->month,
            'basic_salary' => $this->basic_salary,
            'allowances'   => $this->allowances,
            'deductions'   => $this->deductions,
            'net_salary'   => $this->net_salary,
            'status'       => $this->status,
            'notes'        => $this->notes,
        ]);

        $this->dispatch('salaryUpdated');
        session()->flash('success', 'تم تحديث المرتب بنجاح');
    }
    public function save()
{
    return $this->update();
}


    public function render()
    {
        return view('livewire.employees.salaries.edit');
    }
}
