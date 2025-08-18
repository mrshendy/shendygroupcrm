<?php

namespace App\Http\Livewire\Employees\Salaries;

use Livewire\Component;
use App\Models\Salary;
use App\Models\Employee;

class Index extends Component
{
    public $salaries;

    // نموذج
    public $salary_id = null;
    public $employee_id = '';
    public $month = '';
    public $basic_salary = null;
    public $allowances = 0;
    public $deductions = 0;
    public $net_salary = 0;
    public $status = 'pending';
    public $notes = '';

    // 🔎 البحث/الفلترة
    public $search = '';            // اسم الموظف أو الشهر
    public $filter_status = '';     // paid / pending
    public $filter_month = '';      // Y-m محدد

    protected $rules = [
        'employee_id'   => 'required|exists:employees,id',
        'month'         => 'required|date_format:Y-m',
        'basic_salary'  => 'required|numeric|min:0',
        'allowances'    => 'nullable|numeric|min:0',
        'deductions'    => 'nullable|numeric|min:0',
        'net_salary'    => 'required|numeric',
        'status'        => 'required|in:pending,paid',
        'notes'         => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->loadSalaries();
    }

    public function render()
    {
        return view('livewire.employees.salaries.index', [
            'employees' => Employee::select('id','full_name','basic_salary')->orderBy('full_name')->get(),
        ]);
    }

    public function updated($field)
    {
        // إعادة حساب الصافي فورًا
        if (in_array($field, ['basic_salary','allowances','deductions'])) {
            $this->recalcNet();
        }

        // إعادة تحميل الجدول عند تغيير أدوات البحث
        if (in_array($field, ['search','filter_status','filter_month'])) {
            $this->loadSalaries();
        }
    }

    public function recalcNet(): void
    {
        $b = (float)($this->basic_salary ?? 0);
        $a = (float)($this->allowances ?? 0);
        $d = (float)($this->deductions ?? 0);
        $this->net_salary = round($b + $a - $d, 2);
    }

    public function updatedEmployeeId($value)
    {
        // أثناء إضافة جديدة فقط، املا الأساسي تلقائيًا من ملف الموظف
        if ($value && $this->salary_id === null) {
            $emp = Employee::find($value);
            if ($emp && $emp->basic_salary !== null) {
                $this->basic_salary = (float)$emp->basic_salary;
                $this->recalcNet();
            }
        }
    }

    public function loadSalaries()
    {
        $term = trim($this->search);

        $this->salaries = Salary::with('employee')
            ->when($term, function($q) use ($term){
                $like = '%'.$term.'%';
                $q->where(function($qq) use ($like){
                    $qq->whereHas('employee', fn($e)=>$e->where('full_name','like',$like))
                       ->orWhere('month','like',$like);
                });
            })
            ->when($this->filter_status, fn($q)=>$q->where('status', $this->filter_status))
            ->when($this->filter_month, fn($q)=>$q->where('month', $this->filter_month))
            ->orderByDesc('id')
            ->get();
    }

    public function save()
    {
        $this->recalcNet();

        // منع تكرار نفس (الموظف، الشهر)
        $exists = Salary::where('employee_id',$this->employee_id)
            ->where('month',$this->month)
            ->when($this->salary_id, fn($q)=>$q->where('id','!=',$this->salary_id))
            ->exists();
        if ($exists) {
            $this->addError('month','يوجد مسير مسجل لهذا الموظف في نفس الشهر.');
            return;
        }

        $this->validate();

        Salary::updateOrCreate(
            ['id'=>$this->salary_id],
            [
                'employee_id'  => $this->employee_id,
                'month'        => $this->month,
                'basic_salary' => $this->basic_salary ?? 0,
                'allowances'   => $this->allowances ?? 0,
                'deductions'   => $this->deductions ?? 0,
                'net_salary'   => $this->net_salary ?? 0,
                'status'       => $this->status,
                'notes'        => $this->notes,
            ]
        );

        $this->resetInput();
        $this->loadSalaries();
        session()->flash('success','تم الحفظ بنجاح');
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
        session()->flash('success','تم حذف السجل');
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

    // أزرار البحث
    public function clearFilters()
    {
        $this->search = '';
        $this->filter_status = '';
        $this->filter_month = '';
        $this->loadSalaries();
    }
}
