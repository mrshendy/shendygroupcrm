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

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        if ($tx = Transaction::find($id)) {

            // عكس تأثير العملية على الأرصدة
            if ($tx->from_account_id) {
                $from = Account::find($tx->from_account_id);
                if ($from) {
                    $from->current_balance += $tx->amount; // رجع الفلوس اللي اتسحبت
                    $from->save();
                }
            }

            if ($tx->to_account_id) {
                $to = Account::find($tx->to_account_id);
                if ($to) {
                    $to->current_balance -= $tx->amount; // نقص الفلوس اللي كانت اتضافت
                    $to->save();
                }
            }

            // بعد ما رجعنا الرصيد نحذف الحركة
            $tx->delete();

            session()->flash('message','تم حذف الحركة وتعديل الأرصدة بنجاح');
        }
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
