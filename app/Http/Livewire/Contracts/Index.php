<?php

namespace App\Http\Livewire\Contracts;

use App\Models\Contract;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // لو بتستخدم Bootstrap للـ pagination
    protected $paginationTheme = 'bootstrap';

    // ===== فلاتر وخصائص الواجهة =====
    public $search = '';
    public $type = '';
    public $status = '';
    public $perPage = 10;

    // أنواع العقود لعرضها في الفلتر
    public array $types = [];

    // لتخزين الـ ID المطلوب حذفه
    public ?int $deleteId = null;

    // حماية من مشاكل old page عند تغيير الفلاتر
    protected $updatesQueryString = ['search', 'type', 'status', 'perPage'];

    // إعادة ضبط الصفحة عند تغيير أي فلتر
    public function updated($field)
    {
        if (in_array($field, ['search', 'type', 'status', 'perPage'])) {
            $this->resetPage();
        }
    }

    public function mount()
    {
        $this->types = \App\Models\Contract::TYPES;
    }

    // === فتح مودال الحذف
    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        // Livewire v2
        $this->dispatchBrowserEvent('contracts-open-delete');
    }

    // === إلغاء الحذف (اغلاق المودال)
    public function cancelDelete(): void
    {
        $this->reset('deleteId');
        $this->dispatchBrowserEvent('contracts-close-delete');
    }

    // === تنفيذ الحذف
    public function deleteConfirmed(): void
    {
        if ($this->deleteId) {
            $contract = Contract::find($this->deleteId);
            if ($contract) {
                $contract->delete(); // SoftDeletes
                session()->flash('success', 'تم حذف العقد بنجاح.');
            } else {
                session()->flash('error', 'العقد غير موجود.');
            }
        } else {
            session()->flash('error', 'لا يوجد عنصر محدد للحذف.');
        }

        $this->reset('deleteId');
        $this->dispatchBrowserEvent('contracts-close-delete');
        $this->resetPage();
    }

    public function render()
    {
        $contracts = Contract::query()
            ->with(['client', 'project', 'offer', 'items', 'payments'])
            ->when($this->search, function ($q) {
                $s = trim($this->search);
                $q->where(function ($qq) use ($s) {
                    $qq->where('id', $s)
                       ->orWhereHas('client', function ($c) use ($s) {
                           $c->where('name', 'like', "%{$s}%");
                       });
                });
            })
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->latest('id')
            ->paginate((int) $this->perPage);

        return view('livewire.contracts.index', [
            'contracts' => $contracts,
            'types'     => $this->types,
        ]);
    }
}
