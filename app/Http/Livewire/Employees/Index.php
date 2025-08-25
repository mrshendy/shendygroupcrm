<?php

namespace App\Http\Livewire\Employees;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employee;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $deleteId = null; // لتخزين ID الموظف اللي عايز أحذفه
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /** فتح نافذة التأكيد */
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        // هنا نفتح المودال
        $this->dispatchBrowserEvent('showDeleteModal');
    }

    /** الحذف */
    public function delete()
    {
        if ($this->deleteId) {
            $employee = Employee::find($this->deleteId);
            if ($employee) {
                $employee->delete();
            }

            $this->deleteId = null;

            // غلق المودال بعد الحذف
            $this->dispatchBrowserEvent('hideDeleteModal');

            // رسالة نجاح
            session()->flash('message', '✅ تم حذف الموظف بنجاح');
        }
    }

    public function render()
    {
        $employees = Employee::query()
            ->when($this->search, function ($query) {
                $query->where('full_name', 'like', '%' . $this->search . '%')
                      ->orWhere('employee_code', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.employees.index', compact('employees'));
    }
}
