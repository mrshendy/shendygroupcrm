<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use App\Models\Transaction;

class Show extends Component
{
    public Transaction $transaction;

    // حمّل العلاقات المناسبة
    protected $relations = ['fromAccount', 'toAccount', 'item', 'client', 'userAdd'];

    public function mount($transactionId)
    {
        $query = Transaction::query();

        if (!empty($this->relations)) {
            $query->with($this->relations);
        }

        $this->transaction = $query->find($transactionId);

        if (!$this->transaction) {
            abort(404); 
            // أو:
            // return redirect()->route('finance.transactions.index')
            //     ->with('error','المعاملة غير موجودة');
        }
    }

    public function render()
    {
        return view('livewire.finance.transactions.show', [
            'transaction' => $this->transaction,
        ])->layout('layouts.app');
    }
}
