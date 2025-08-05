<?php

namespace App\Http\Livewire\Employees;

use Livewire\Component;
use App\Models\Employee;

class Create extends Component
{
    public $full_name;
    public $employee_code;
    public $email;
    public $phone;
    public $job_title;
    public $department;
    public $employment_status;
    public $employment_type;
    public $salary;
    public $hiring_date;
    public $birth_date;
    public $gender;
    public $address;
    public $notes;
    public $status = 'مفعل';

    protected $rules = [
        'full_name' => 'required|string',
        'employee_code' => 'required|string|unique:employees,employee_code',
        'email' => 'required|email|unique:employees,email',
        'phone' => 'nullable|string',
        'job_title' => 'required|string',
        'employment_status' => 'required|in:دائم,متعاقد,تحت التدريب',
        'employment_type' => 'required|in:دوام كامل,دوام جزئي',
        'salary' => 'nullable|numeric',
        'hiring_date' => 'nullable|date',
        'birth_date' => 'nullable|date',
        'gender' => 'required|in:ذكر,أنثى',
        'address' => 'nullable|string',
        'notes' => 'nullable|string',
        'status' => 'required|in:مفعل,غير مفعل',
    ];

    public function save()
    {
        $this->validate();

        Employee::create([
            'full_name' => $this->full_name,
            'employee_code' => $this->employee_code,
            'email' => $this->email,
            'phone' => $this->phone,
            'job_title' => $this->job_title,
            'department' => $this->department,
            'employment_status' => $this->employment_status,
            'employment_type' => $this->employment_type,
            'salary' => $this->salary,
            'hiring_date' => $this->hiring_date,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'address' => $this->address,
            'notes' => $this->notes,
            'status' => $this->status,
        ]);

        session()->flash('success', 'تم إضافة الموظف بنجاح.');
        $this->reset(); // لإفراغ جميع الحقول بعد الإضافة
    }

    public function render()
    {
        return view('livewire.employees.create');
    }
}
