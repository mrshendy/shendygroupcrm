<?php

namespace App\Http\Livewire\Employees\Leaves;

use Livewire\Component;
use App\Models\Leave;

class Index extends Component
{
    public $leaves;
    public $listeners = ['leaveAdded' => 'refreshLeaves'];

    public function refreshLeaves()
{
    $this->leaves = Leave::with('employee')
        ->latest()
        ->get();
}
    public function mount()
    {
        // عرض كل الإجازات بموظفيها
        $this->leaves = Leave::with('employee')
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.employees.leaves.index');
    }
}
