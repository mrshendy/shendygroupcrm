<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use App\Models\Transaction;

class Show extends Component
{
    public ?Transaction $transaction = null;

    // العلاقات المراد تحميلها
    protected array $relations = [
        'fromAccount',
        'toAccount',
        'item',
        'client',
        'userAdd',
    ];

    /**
     * تحميل المعاملة باستخدام Route Model Binding
     */
    public function mount(Transaction $transaction)
    {
        // تحميل العلاقات
        $this->transaction = $transaction->load($this->relations);
    }

    public function render()
    {
        return view('livewire.finance.transactions.show', [
            'transaction' => $this->transaction,
        ])->layout('layouts.app');
    }
}
