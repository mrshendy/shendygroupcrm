<?php

namespace App\Http\Livewire\Employees\Salaries;

use Livewire\Component;
use App\Models\Salary;
use App\Models\Employee;

class Edit extends Component
{
    public $salaryId;
    public $employee_id, $month, $basic_salary, $allowances, $deductions, $net_salary, $status, $notes;
    public $employees = [];

    public function mount($salaryId)
    {
        // جلب كل الموظفين عشان نعرضهم في select
        $this->employees = Employee::select('id', 'full_name')->get();

        // جلب السجل المراد تعديله
        $salary = Salary::findOrFail($salaryId);

        // تعبئة الخصائص من قاعدة البيانات
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

        
        // التحقق
        $this->validate([
            'employee_id'  => 'required|exists:employees,id',
            'month'        => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
            'allowances'   => 'nullable|numeric|min:0',
            'deductions'   => 'nullable|numeric|min:0',
            'net_salary'   => 'nullable|numeric|min:0',
            'status'       => 'required|string',
        ], [
            'employee_id.required'  => 'يجب اختيار الموظف',
            'month.required'        => 'يجب إدخال الشهر',
            'basic_salary.required' => 'يجب إدخال الراتب الأساسي',
        ]);

        // تعديل السجل
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

        session()->flash('success', 'تم تحديث المرتب بنجاح ✅');

        // مع Livewire v2
        $this->emit('salaryUpdated');

        // مع Livewire v3 (لو محتاج)
        // $this->dispatch('salaryUpdated')->to(\App\Http\Livewire\Employees\Salaries\Index::class);
    }
    public function updated($property)
{
    if (in_array($property, ['basic_salary', 'allowances', 'deductions'])) {
        $this->net_salary = ($this->basic_salary + $this->allowances) - $this->deductions;
    }
}


    public function render()
    {
        return view('livewire.employees.salaries.edit');
    }
}
