<?php

namespace App\Http\Livewire\Finance\Items;

use Livewire\Component;
use App\Models\Item;

class Edit extends Component
{
    public $item_id;
    public $name;
    public $type;
    public $status;

    protected $rules = [
        'name'   => 'required|string|min:3|max:150',
        'type'   => 'required|in:مصروف,إيراد',
        'status' => 'required|in:active,inactive',
    ];

    public function mount($id)
    {
        $item = Item::findOrFail($id);

        $this->item_id = $item->id;
        $this->name    = $item->name;
        $this->type    = $item->type;
        $this->status  = $item->status;
    }

    public function update()
    {
        $this->validate();

        $item = Item::findOrFail($this->item_id);
        $item->update([
            'name'   => $this->name,
            'type'   => $this->type,
            'status' => $this->status,
        ]);

        session()->flash('success', 'تم تعديل البند بنجاح ✅');
        return redirect()->route('finance.items.index');
    }

    public function render()
    {
        return view('livewire.finance.items.edit')
            ->layout('layouts.app');
    }
}
