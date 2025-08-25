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
    public $itemToDelete = null; // ✅ لتخزين العنصر المطلوب حذفه

    protected $paginationTheme = 'bootstrap';

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

    /** إضافة بند جديد */
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

    /** تعديل بند */
    public function edit($id)
    {
        $item         = Item::findOrFail($id);
        $this->itemId = $item->id;
        $this->name   = $item->name;
        $this->type   = $item->type;
        $this->status = $item->status;
        $this->updateMode = true;
    }

    /** تحديث بند */
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

    /** إلغاء التعديل */
    public function cancelUpdate()
    {
        $this->resetForm();
    }

    /** إعادة ضبط البيانات */
    private function resetForm()
    {
        $this->reset(['name', 'type', 'status', 'updateMode', 'itemId']);
        $this->status = 'active';
    }

    /** البحث */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /** فتح نافذة الحذف */
    public function confirmDelete($id)
    {
        $this->itemToDelete = $id;
        $this->dispatchBrowserEvent('showDeleteModal');
    }

    /** تنفيذ الحذف */
    public function deleteConfirmed()
    {
        if ($this->itemToDelete) {
            $item = Item::find($this->itemToDelete);
            if ($item) {
                $item->delete();
                session()->flash('message', '✅ تم حذف البند بنجاح.');
            }
            $this->itemToDelete = null;
            $this->dispatchBrowserEvent('hideDeleteModal');
        }
    }

    /** عرض الصفحة */
    public function render()
    {
        $items = Item::query()
            ->when($this->search, function($q) {
                $q->where(function($sub) {
                    $sub->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('type', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.finance.items.manage', compact('items'));
    }
}
