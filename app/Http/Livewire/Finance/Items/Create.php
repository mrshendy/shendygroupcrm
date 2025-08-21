<?php

namespace App\Http\Livewire\Finance\Items;

use Livewire\Component;
use App\Models\Item;

class Create extends Component
{
    public $name, $type, $status = 'active';

    protected $rules = [
        'name'   => 'required|string|max:255',
        'type'   => 'required|string|max:255',
        'status' => 'required|in:active,inactive',
    ];

    protected $messages = [
        'name.required'   => 'اسم البند مطلوب',
        'type.required'   => 'النوع مطلوب',
        'status.required' => 'الحالة مطلوبة',
    ];

    public function save()
    {
        $this->validate();

        Item::create([
            'name'   => $this->name,
            'type'   => $this->type,
            'status' => $this->status,
        ]);

        $this->reset(['name', 'type']);
        $this->status = 'active';

        session()->flash('success', 'تم حفظ البند بنجاح');
        $this->dispatch('itemAdded');
    }

    public function render()
    {
        return view('livewire.finance.items.create');
    }
}
