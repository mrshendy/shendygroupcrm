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

    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /** فتح المودال */
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatchBrowserEvent('showDeleteModal');
    }

    /** تنفيذ الحذف (Soft Delete) */
    public function delete(AccountService $accountService)
    {
        if ($this->deleteId) {
            $tx = Transaction::find($this->deleteId);

            if ($tx) {
                try {
                    // إرجاع تأثير العملية قبل الحذف
                    $accountService->revertTransaction($tx);

                    // Soft Delete بدل الحذف النهائي
                    $tx->delete();

                    session()->flash('message', '✅ تم حذف الحركة (Soft Delete) وتعديل الأرصدة بنجاح');
                } catch (\Exception $e) {
                    session()->flash('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
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
