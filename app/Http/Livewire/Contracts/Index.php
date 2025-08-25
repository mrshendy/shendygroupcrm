<?php

namespace App\Http\Livewire\Contracts;

use App\Models\Contract;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $type = '';
    public $status = '';
    public $perPage = 10;

    public $contractToDelete = null; // لتخزين ID العقد

    protected $queryString = [
        'search' => ['except' => ''],
        'type'   => ['except' => ''],
        'status' => ['except' => ''],
        'page'   => ['except' => 1],
    ];

    /** إعادة ضبط الصفحة عند تحديث الفلاتر */
    public function updatingSearch() { $this->resetPage(); }
    public function updatingType()   { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }
    public function updatingPerPage(){ $this->resetPage(); }

    /** فتح نافذة التأكيد */
    public function confirmDelete(int $id): void
    {
        $this->contractToDelete = $id;
        $this->dispatchBrowserEvent('open-delete-modal');
    }

    /** تنفيذ الحذف (Soft Delete) */
    public function deleteConfirmed(): void
    {
        if ($this->contractToDelete) {
            $contract = Contract::find($this->contractToDelete);

            if ($contract) {
                // Soft Delete
                $contract->delete();
                session()->flash('success', '✅ تم حذف العقد (Soft Delete) بنجاح.');
            }

            $this->contractToDelete = null;

            // إعادة تحميل الصفحة
            $this->resetPage();

            $this->dispatchBrowserEvent('close-delete-modal');
        }
    }

    /** التحقق من وجود ملف العقد */
    public function getFileExistsAttribute(): bool
    {
        return $this->contract_file && Storage::disk('public')->exists($this->contract_file);
    }

    /** جلب رابط الملف */
    public function getFileUrlAttribute(): ?string
    {
        return $this->contract_file ? asset('storage/'.$this->contract_file) : null;
    }

    public function render()
    {
        $search = trim((string) $this->search);

        $contracts = Contract::with(['client', 'project', 'offer', 'items', 'payments'])
            ->when($search !== '', function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('id', (int) $search);
                } else {
                    $q->whereHas('client', fn ($c) => $c->where('name', 'like', "%{$search}%"));
                }
            })
            ->when($this->type !== '', fn ($q) => $q->where('type', $this->type))
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->orderByDesc('id')
            ->paginate((int) $this->perPage);

        return view('livewire.contracts.index', [
            'contracts' => $contracts,
            'types'     => \App\Models\Contract::TYPES ?? [],
        ])->layout('layouts.master', ['title' => 'قائمة التعاقدات']);
    }
}
