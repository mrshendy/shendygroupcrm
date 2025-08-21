<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use App\Models\Transaction;

class Show extends Component
{
    public Transaction $transaction;

    // العلاقات المراد تحميلها
    protected array $relations = [
        'fromAccount',
        'toAccount',
        'item',
        'client',
        'userAdd'
    ];

    public function mount(int $transactionId)
    {
        $this->transaction = Transaction::with($this->relations)->findOrFail($transactionId);
    }

    public function render()
    {
        return view('livewire.finance.transactions.show', [
            'transaction' => $this->transaction,
        ])->layout('layouts.app');
    }
}
