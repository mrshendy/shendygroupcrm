<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Services\AccountService;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $deleteId = null;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

   public function confirmDelete($id)
{
    $this->deleteId = $id;
    $this->dispatchBrowserEvent('showDeleteModal');
}

public function deleteConfirmed(AccountService $accountService)
{
    if ($this->deleteId) {
        $tx = Transaction::find($this->deleteId);

        if ($tx) {
            try {
                $accountService->revertTransaction($tx);
                $tx->delete();
                session()->flash('message', 'تم حذف الحركة وتعديل الأرصدة بنجاح');
            } catch (\Exception $e) {
                session()->flash('error', $e->getMessage());
            }
        }
    }

    $this->deleteId = null;
    $this->dispatchBrowserEvent('hideDeleteModal');
}

    public function render()
    {
        $transactions = Transaction::with(['fromAccount','toAccount','item','client'])
            ->when($this->search, function($q){
                $q->where('notes','like',"%{$this->search}%")
                  ->orWhere('collection_type','like',"%{$this->search}%")
                  ->orWhere('amount','like',"%{$this->search}%");
            })
            ->latest()
            ->paginate(10);

        return view('livewire.finance.transactions.index', compact('transactions'));
    }
}
