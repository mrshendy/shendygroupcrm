<?php

namespace App\Http\Livewire\Employees;

use Livewire\Component;
use App\Models\Employee;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search']; // استبدال updatesQueryString بـ queryString
    protected $paginationTheme = 'bootstrap'; // إضافة إذا كنت تستخدم Bootstrap للتبويب

    public function updatingSearch()
    {
        $this->resetPage();
    }
    

    public function render()
    {
        $employees = Employee::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('full_name', 'like', '%' . $this->search . '%')
                      ->orWhere('employee_code', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('id') // طريقة أحدث لترتيب تنازلي
            ->paginate(10);

        return view('livewire.employees.index', compact('employees'));
    }
}