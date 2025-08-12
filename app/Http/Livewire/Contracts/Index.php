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

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'status' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        Contract::findOrFail($id)->delete();
        session()->flash('success', 'تم حذف العقد بنجاح.');
        $this->resetPage();
    }

    public function getFileExistsAttribute(): bool
    {
        return $this->contract_file && Storage::disk('public')->exists($this->contract_file);
    }

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
            'types' => \App\Models\Contract::TYPES ?? [],
        ])->layout('layouts.master', ['title' => 'قائمة التعاقدات']);
    }
}
