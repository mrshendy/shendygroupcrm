<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\User;

class Show extends Component
{
    public Transaction $transaction;

    // لو حابب تحمّل علاقات إضافية (عدّل حسب مشروعك)
    protected $relations = ['account', 'item'];

    public function mount($transactionId)
    {
        $query = Transaction::query();

        if (!empty($this->relations)) {
            $query->with($this->relations);
        }

        $this->transaction = $query->find($transactionId);

        if (!$this->transaction) {
            abort(404); // أو redirect()->route('finance.transactions.index');
        }
    }

    public function render()
    {
        return view('livewire.finance.transactions.show')
            ->layout('layouts.app'); // لو عندك لياوت عام
    }
}
