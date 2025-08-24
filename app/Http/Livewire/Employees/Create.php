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

    /** ✅ قواعد التحقق */
    protected $rules = [
        'full_name'         => 'required|string|min:3|max:255',
        'employee_code'     => 'required|string|min:2|max:50|unique:employees,employee_code',
        'email'             => 'required|email|unique:employees,email',
        'phone'             => 'required|string|min:8|max:20',
        'job_title'         => 'required|string|min:2|max:255',
        'department'        => 'required|string|min:2|max:255',
        'employment_status' => 'required|in:دائم,متعاقد,تحت التدريب',
        'employment_type'   => 'required|in:دوام كامل,دوام جزئي',
        'salary'            => 'required|numeric|min:0',
        'hiring_date'       => 'required|date',
        'birth_date'        => 'required|date|before:today',
        'gender'            => 'required|in:ذكر,أنثى',
        'address'           => 'required|string|min:5|max:255',
        'notes'             => 'nullable|string|max:500',
        'status'            => 'required|in:مفعل,غير مفعل',
    ];

    /** ✅ رسائل التحقق بالعربي */
    protected $messages = [
        'full_name.required'     => 'الاسم الكامل مطلوب.',
        'full_name.min'          => 'الاسم يجب أن يكون 3 أحرف على الأقل.',
        'employee_code.required' => 'كود الموظف مطلوب.',
        'employee_code.unique'   => 'هذا الكود مستخدم بالفعل.',
        'email.required'         => 'البريد الإلكتروني مطلوب.',
        'email.email'            => 'صيغة البريد الإلكتروني غير صحيحة.',
        'email.unique'           => 'هذا البريد مستخدم بالفعل.',
        'phone.required'         => 'رقم الهاتف مطلوب.',
        'phone.min'              => 'رقم الهاتف يجب أن يكون 8 أرقام على الأقل.',
        'job_title.required'     => 'المسمى الوظيفي مطلوب.',
        'department.required'    => 'القسم مطلوب.',
        'employment_status.required' => 'حالة التوظيف مطلوبة.',
        'employment_status.in'   => 'حالة التوظيف غير صحيحة.',
        'employment_type.required' => 'نوع التوظيف مطلوب.',
        'employment_type.in'     => 'نوع التوظيف غير صحيح.',
        'salary.required'        => 'الراتب مطلوب.',
        'salary.numeric'         => 'الراتب يجب أن يكون رقم.',
        'hiring_date.required'   => 'تاريخ التعيين مطلوب.',
        'birth_date.required'    => 'تاريخ الميلاد مطلوب.',
        'birth_date.before'      => 'تاريخ الميلاد يجب أن يكون قبل اليوم.',
        'gender.required'        => 'النوع مطلوب.',
        'gender.in'              => 'النوع يجب أن يكون ذكر أو أنثى.',
        'address.required'       => 'العنوان مطلوب.',
        'notes.max'              => 'الملاحظات يجب ألا تتجاوز 500 حرف.',
        'status.required'        => 'حالة الحساب مطلوبة.',
        'status.in'              => 'حالة الحساب غير صحيحة.',
    ];

    /** حفظ الموظف */
    public function save()
    {
        $this->validate();

        Employee::create([
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

        session()->flash('success', '✅ تم إضافة الموظف بنجاح.');
        $this->reset(); // تفريغ الحقول بعد الحفظ
    }

    public function render()
    {
        return view('livewire.employees.create')->layout('layouts.master');
    }
}
