<?php

namespace App\Http\Livewire\Employees\Salaries;

use Livewire\Component;
use App\Models\Salary;
use App\Models\Employee;

class Index extends Component
{
    public $salaries;

    // حقول النموذج
    public $salary_id = null;
    public $employee_id = '';
    public $month = '';
    public $basic_salary = null;
    public $allowances = 0;
    public $deductions = 0;
    public $net_salary = 0;
    public $status = 'pending';
    public $notes = '';

    protected $rules = [
        'employee_id'   => 'required|exists:employees,id',
        'month'         => 'required|date_format:Y-m',
        'basic_salary'  => 'required|numeric|min:0',
        'allowances'    => 'nullable|numeric|min:0',
        'deductions'    => 'nullable|numeric|min:0',
        'net_salary'    => 'required|numeric', // محسوبة تلقائيًا لكن نتحقق منها
        'status'        => 'required|in:pending,paid',
        'notes'         => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'employee_id.required'  => 'اختر الموظف',
        'month.required'        => 'اختر الشهر',
        'basic_salary.required' => 'أدخل الراتب الأساسي',
    ];

    public function mount()
    {
        $this->loadSalaries();
    }

    public function render()
    {
        return view('livewire.employees.salaries.index', [
            // نعرض الاسم والراتب الأساسي لملء النموذج تلقائيًا
            'employees' => Employee::select('id','full_name','basic_salary')->orderBy('full_name')->get(),
        ]);
    }

    public function loadSalaries()
    {
        $this->salaries = Salary::with('employee')->orderByDesc('id')->get();
    }

    public function updated($field)
    {
        if (in_array($field, ['basic_salary','allowances','deductions'])) {
            $this->recalcNet();
        }
    }

    public function updatedEmployeeId($value)
    {
        // عند اختيار الموظف لأول مرة (أثناء إضافة جديدة)، املأ الراتب الأساسي تلقائيًا
        if ($value && $this->salary_id === null) {
            $emp = Employee::find($value);
            if ($emp && $emp->basic_salary !== null) {
                $this->basic_salary = (float)$emp->basic_salary;
                $this->recalcNet();
            }
        }
    }

    protected function recalcNet(): void
    {
        $b = (float)($this->basic_salary ?? 0);
        $a = (float)($this->allowances ?? 0);
        $d = (float)($this->deductions ?? 0);
        $this->net_salary = round($b + $a - $d, 2);
    }

    public function save()
    {
        // احسب الصافي أولًا
        $this->recalcNet();

        // منع تكرار نفس الشهر لنفس الموظف
        $exists = Salary::where('employee_id', $this->employee_id)
            ->where('month', $this->month)
            ->when($this->salary_id, fn($q)=>$q->where('id','!=',$this->salary_id))
            ->exists();
        if ($exists) {
            $this->addError('month', 'يوجد مسير مسجل لهذا الموظف في نفس الشهر.');
            return;
        }

        $this->validate();

        Salary::updateOrCreate(
            ['id' => $this->salary_id],
            [
                'employee_id'   => $this->employee_id,
                'month'         => $this->month,
                'basic_salary'  => $this->basic_salary ?? 0,
                'allowances'    => $this->allowances ?? 0,
                'deductions'    => $this->deductions ?? 0,
                'net_salary'    => $this->net_salary ?? 0, // ✅ محسوبة
                'status'        => $this->status,
                'notes'         => $this->notes,
            ]
        );

        $this->resetInput();
        $this->loadSalaries();
        session()->flash('success', 'تم الحفظ بنجاح');
    }

    public function edit($id)
    {
        $s = Salary::findOrFail($id);

        $this->salary_id    = $s->id;
        $this->employee_id  = $s->employee_id;
        $this->month        = $s->month;
        $this->basic_salary = (float)$s->basic_salary;
        $this->allowances   = (float)$s->allowances;
        $this->deductions   = (float)$s->deductions;
        $this->net_salary   = (float)$s->net_salary;
        $this->status       = $s->status;
        $this->notes        = $s->notes;
    }

    public function delete($id)
    {
        Salary::findOrFail($id)->delete();
        $this->loadSalaries();
        session()->flash('success', 'تم حذف السجل');
    }

    public function resetInput()
    {
        $this->salary_id    = null;
        $this->employee_id  = '';
        $this->month        = '';
        $this->basic_salary = null;
        $this->allowances   = 0;
        $this->deductions   = 0;
        $this->net_salary   = 0;
        $this->status       = 'pending';
        $this->notes        = '';
        $this->resetValidation();
    }
}
