<?php

namespace App\Http\Livewire\Shifts;

use Livewire\Component;
use App\Models\Shift;

class Manage extends Component
{
    public $shift_id;
    public $name;
    public $days = [];
    public $start_time;
    public $end_time;
    public $leave_allowance;

    protected $rules = [
        'name' => 'required|string|max:255',
        'days' => 'required|array|min:1',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        'leave_allowance' => 'required|integer|min:0'
    ];

    public function save()
    {
        $this->validate();

        Shift::updateOrCreate(
            ['id' => $this->shift_id],
            [
                'name' => $this->name,
                'days' => $this->days,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'leave_allowance' => $this->leave_allowance,
            ]
        );

        session()->flash('success', $this->shift_id ? 'تم تحديث الشيفت' : 'تم إنشاء الشيفت');

        $this->resetForm();
    }

    public function edit($id)
    {
        $shift = Shift::findOrFail($id);
        $this->shift_id = $shift->id;
        $this->name = $shift->name;
        $this->days = $shift->days;
        $this->start_time = $shift->start_time;
        $this->end_time = $shift->end_time;
        $this->leave_allowance = $shift->leave_allowance;
    }

    public function delete($id)
    {
        Shift::findOrFail($id)->delete();
        session()->flash('success', 'تم حذف الشيفت');
    }

    public function resetForm()
    {
        $this->reset(['shift_id', 'name', 'days', 'start_time', 'end_time', 'leave_allowance']);
    }

    public function render()
    {
        return view('livewire.shifts.manage', [
            'shifts' => Shift::latest()->get()
        ]);
    }
}
