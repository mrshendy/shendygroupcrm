<?php

namespace App\Http\Livewire\Employees\Leaves;

use Livewire\Component;
use App\Models\Leave;
use App\Models\LeaveBalance;

class Index extends Component
{
    public $leaves = [];

    protected $listeners = ['leaveAdded' => 'loadLeaves'];

    public function mount()
    {
        $this->loadLeaves();
    }

    public function loadLeaves()
    {
        $this->leaves = Leave::with(['employee'])->latest()->get();
    }

    public function render()
    {
        return view('livewire.employees.leaves.index', [
            'leaves' => $this->leaves,
        ]);
    }
}
