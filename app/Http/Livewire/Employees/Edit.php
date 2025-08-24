<?php

namespace App\Http\Livewire\Employees;

use Livewire\Component;
use App\Models\Employee;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public $employee_id;
    public $full_name, $employee_code, $email, $phone, $job_title, $department;
    public $employment_status, $employment_type, $salary, $hiring_date, $birth_date;
    public $gender, $address, $notes, $status;

    protected function rules()
    {
        return [
            'full_name'         => 'required|string',
            'employee_code'     => [
                'required',
                'string',
                Rule::unique('employees', 'employee_code')->ignore($this->employee_id),
            ],
            'email'             => [
                'required',
                'email',
                Rule::unique('employees', 'email')->ignore($this->employee_id),
            ],
            'phone'             => 'nullable|string',
            'job_title'         => 'required|string',
            'department'        => 'nullable|string',
            'employment_status' => 'required|in:دائم,متعاقد,تحت التدريب',
            'employment_type'   => 'required|in:دوام كامل,دوام جزئي',
            'salary'            => 'nullable|numeric',
            'hiring_date'       => 'nullable|date',
            'birth_date'        => 'nullable|date',
            'gender'            => 'required|in:ذكر,أنثى',
            'address'           => 'nullable|string',
            'notes'             => 'nullable|string',
            'status'            => 'required|in:مفعل,غير مفعل',
        ];
    }

    public function mount($id)
    {
        $employee = Employee::findOrFail($id);

        $this->employee_id       = $employee->id;
        $this->full_name         = $employee->full_name;
        $this->employee_code     = $employee->employee_code;
        $this->email             = $employee->email;
        $this->phone             = $employee->phone;
        $this->job_title         = $employee->job_title;
        $this->department        = $employee->department;
        $this->employment_status = $employee->employment_status;
        $this->employment_type   = $employee->employment_type;
        $this->salary            = $employee->salary;

        // ✅ تصحيح التواريخ ليظهروا في input[type=date]
        $this->hiring_date = $employee->hiring_date 
            ? $employee->hiring_date->format('Y-m-d') 
            : null;

        $this->birth_date = $employee->birth_date 
            ? $employee->birth_date->format('Y-m-d') 
            : null;

        $this->gender  = $employee->gender;
        $this->address = $employee->address;
        $this->notes   = $employee->notes;
        $this->status  = $employee->status;
    }

    public function update()
    {
        $this->validate();

        $employee = Employee::findOrFail($this->employee_id);

        $employee->update([
            'full_name'         => $this->full_name,
            'employee_code'     => $this->employee_code,
            'email'             => $this->email,
            'phone'             => $this->phone,
            'job_title'         => $this->job_title,
            'department'        => $this->department,
            'employment_status' => $this->employment_status,
            'employment_type'   => $this->employment_type,
            'salary'            => $this->salary,
            'hiring_date'       => $this->hiring_date,
            'birth_date'        => $this->birth_date,
            'gender'            => $this->gender,
            'address'           => $this->address,
            'notes'             => $this->notes,
            'status'            => $this->status,
        ]);

        session()->flash('success', 'تم تحديث بيانات الموظف بنجاح.');
        return redirect()->route('employees.index');
    }

    public function render()
    {
        return view('livewire.employees.edit');
    }
}
