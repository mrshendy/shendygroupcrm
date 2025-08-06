<?php

namespace App\Http\Livewire\Finance\Items;

use Livewire\Component;
use App\Models\Item;

class Create extends Component
{
    public $name;
    public $type;
    public $status = 'active';

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'status' => 'required|in:active,inactive',
    ];

    public function save()
    {
        $this->validate();

        Item::create([
            'name' => $this->name,
            'type' => $this->type,
            'status' => $this->status,
        ]);

        // Reset form fields
        $this->reset(['name', 'type', 'status']);
        $this->status = 'active'; // default again

        session()->flash('success', 'تم حفظ البند بنجاح');
        $this->emit('itemAdded'); // لإعادة التحديث في القائمة
    }

    public function render()
    {
        return view('livewire.finance.items.create');
    }
}