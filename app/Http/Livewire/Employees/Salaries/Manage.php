<?php

namespace App\Http\Livewire\Employees\Salaries;

use Livewire\Component;
use App\Models\Salary;
use App\Models\Employee;

class Manage extends Component
{
    public $salary_id;
    public $employee_id;
    public $month;
    public $basic_salary = 0;
    public $allowances = 0;
    public $deductions = 0;
    public $status = 'pending';
    public $notes;

    public $employees = [];
    public $salaries = [];

    protected $rules = [
        'employee_id'   => 'required|exists:employees,id',
        'month'         => 'required|date_format:Y-m',
        'basic_salary'  => 'nullable|numeric|min:0',
        'allowances'    => 'nullable|numeric|min:0',
        'deductions'    => 'nullable|numeric|min:0',
        'status'        => 'required|in:pending,paid',
        'notes'         => 'nullable|string|max:500',
    ];

    protected $messages = [
        'employee_id.required' => 'الرجاء اختيار الموظف.',
        'employee_id.exists'   => 'الموظف غير موجود.',
        'month.required'       => 'الرجاء تحديد الشهر.',
        'month.date_format'    => 'صيغة الشهر غير صحيحة.',
        'status.required'      => 'الرجاء تحديد حالة المرتب.',
    ];

    public function mount()
    {
        $this->employees = Employee::orderBy('full_name')->get();
        $this->loadSalaries();
    }

    public function loadSalaries()
    {
        $this->salaries = Salary::with('employee')->latest()->get();
    }

    public function save()
    {
        $data = $this->validate();

        $data['net_salary'] = ($this->basic_salary ?? 0) + ($this->allowances ?? 0) - ($this->deductions ?? 0);

        if ($this->salary_id) {
            Salary::findOrFail($this->salary_id)->update($data);
            session()->flash('success', 'تم تعديل المرتب بنجاح.');
        } else {
            Salary::create($data);
            session()->flash('success', 'تم إضافة المرتب بنجاح.');
        }

        $this->resetForm();
        $this->loadSalaries();
    }

    public function edit($id)
    {
        $salary = Salary::findOrFail($id);

        $this->salary_id    = $salary->id;
        $this->employee_id  = $salary->employee_id;
        $this->month        = $salary->month;
        $this->basic_salary = $salary->basic_salary;
        $this->allowances   = $salary->allowances;
        $this->deductions   = $salary->deductions;
        $this->status       = $salary->status;
        $this->notes        = $salary->notes;
    }

    public function delete($id)
    {
        Salary::findOrFail($id)->delete();
        session()->flash('success', 'تم حذف المرتب بنجاح.');
        $this->loadSalaries();
    }

    public function resetForm()
    {
        $this->salary_id = null;
        $this->employee_id = null;
        $this->month = null;
        $this->basic_salary = 0;
        $this->allowances = 0;
        $this->deductions = 0;
        $this->status = 'pending';
        $this->notes = null;
    }

    public function render()
    {
        return view('livewire.employees.salaries.manage');
    }
}
