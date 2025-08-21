<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Models\Account;

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

    public function deleteConfirmed()
    {
        if ($this->deleteId) {
            $tx = Transaction::find($this->deleteId);

            if ($tx) {
                // رجوع تأثير العملية على الأرصدة
                if ($tx->from_account_id) {
                    $from = Account::find($tx->from_account_id);
                    if ($from) {
                        $from->current_balance += $tx->amount;
                        $from->save();
                    }
                }

                if ($tx->to_account_id) {
                    $to = Account::find($tx->to_account_id);
                    if ($to) {
                        $to->current_balance -= $tx->amount;
                        $to->save();
                    }
                }

                $tx->delete();
                session()->flash('message', 'تم حذف الحركة وتعديل الأرصدة بنجاح');
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
