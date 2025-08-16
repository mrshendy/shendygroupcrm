<?php

namespace App\Http\Livewire\Employees\Salaries;

use Livewire\Component;
use App\Models\Salary;
use App\Models\Employee;

class Index extends Component
{
    public $salaries;
    public $employee_id, $month, $basic_salary, $allowances, $deductions, $net_salary, $status, $notes;
    public $salary_id;

    protected $rules = [
        'employee_id' => 'required|exists:employees,id',
        'month' => 'required|date_format:Y-m',
        'basic_salary' => 'required|numeric',
        'allowances' => 'nullable|numeric',
        'deductions' => 'nullable|numeric',
        'net_salary' => 'required|numeric',
        'status' => 'required|in:pending,paid',
        'notes' => 'nullable|string',
    ];

    public function mount()
    {
        $this->loadSalaries();
    }

    public function loadSalaries()
    {
        $this->salaries = Salary::with('employee')->latest()->get();
    }

    public function save()
    {
        $this->validate();

     Salary::updateOrCreate(
    ['id' => $this->salary_id],
    [
        'employee_id' => $this->employee_id,
        'month' => $this->month,
        'allowances' => $this->allowances,
        'deductions' => $this->deductions,
        'net_salary' => $this->net_salary,
        'status' => $this->status,
        'notes' => $this->notes,
    ]
);


        $this->resetInput();
        $this->loadSalaries();
        session()->flash('success', 'تم الحفظ بنجاح');
    }

    public function edit($id)
    {
        $salary = Salary::findOrFail($id);
        $this->salary_id = $salary->id;
        $this->employee_id = $salary->employee_id;
        $this->month = $salary->month;
        $this->basic_salary = $salary->basic_salary;
        $this->allowances = $salary->allowances;
        $this->deductions = $salary->deductions;
        $this->net_salary = $salary->net_salary;
        $this->status = $salary->status;
        $this->notes = $salary->notes;
    }

    public function delete($id)
    {
        Salary::findOrFail($id)->delete();
        $this->loadSalaries();
    }

    public function resetInput()
    {
        $this->salary_id = null;
        $this->employee_id = '';
        $this->month = '';
        $this->basic_salary = '';
        $this->allowances = '';
        $this->deductions = '';
        $this->net_salary = '';
        $this->status = 'pending';
        $this->notes = '';
    }

    public function render()
    {
        return view('livewire.employees.salaries.index', [
            'employees' => Employee::all()
        ]);
    }
}
