<?php

namespace App\Http\Livewire\Finance\Items;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Item;

class Manage extends Component
{
    use WithPagination;

    public $name, $type, $status = 'active', $search = '';
    public $updateMode = false, $itemId;

    protected $rules = [
        'name'   => 'required|string|max:255',
        'type'   => 'required|string|max:255',
        'status' => 'required|in:active,inactive',
    ];

    public function save()
    {
        $this->validate();

        Item::create([
            'name'   => $this->name,
            'type'   => $this->type,
            'status' => $this->status,
        ]);

        session()->flash('message', 'تم إضافة البند بنجاح!');
        $this->resetForm();
    }

    public function edit($id)
    {
        $item        = Item::findOrFail($id);
        $this->itemId = $item->id;
        $this->name   = $item->name;
        $this->type   = $item->type;
        $this->status = $item->status;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate();

        $item = Item::findOrFail($this->itemId);
        $item->update([
            'name'   => $this->name,
            'type'   => $this->type,
            'status' => $this->status,
        ]);

        session()->flash('message', 'تم تحديث البند بنجاح!');
        $this->resetForm();
    }

    public function cancelUpdate()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['name', 'type', 'status', 'updateMode', 'itemId']);
        $this->status = 'active';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $items = Item::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('type', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.finance.items.manage', compact('items'));
    }
}
