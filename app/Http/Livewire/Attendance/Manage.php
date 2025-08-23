<?php

namespace App\Http\Livewire\Attendance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Attendance;
use Carbon\Carbon;

class Manage extends Component
{
    use WithPagination;

    public $filterDate;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        // افتراضياً يعرض اليوم الحالي
        $this->filterDate = Carbon::today()->toDateString();
    }

    public function updatingFilterDate()
    {
        $this->resetPage(); // لو غيرت التاريخ يرجع للصفحة الأولى
    }

    public function render()
    {
        $attendances = Attendance::with('employee')
            ->whereDate('attendance_date', $this->filterDate)
            ->orderBy('check_in', 'asc')
            ->paginate(10);

        return view('livewire.attendance.manage', [
            'attendances' => $attendances
        ]);
    }
}
