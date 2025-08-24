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

    protected $listeners = ['deleteConfirmed' => 'delete']; 

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /** فتح نافذة التأكيد */
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatchBrowserEvent('show-delete-confirmation');
    }

    /** الحذف */
    public function delete()
    {
        if ($this->deleteId) {
            $employee = Employee::findOrFail($this->deleteId);
            $employee->delete();

            $this->deleteId = null;
            $this->dispatchBrowserEvent('employee-deleted');
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
