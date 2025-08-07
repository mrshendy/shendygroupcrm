<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Item;

class Create extends Component
{
    public $account_id;
    public $item_id;
    public $amount;
    public $notes;
    public $transaction_date;
    public $transaction_type;

    protected $rules = [
        'account_id' => 'required|exists:accounts,id',
        'item_id' => 'required|exists:items,id',
        'amount' => 'required|numeric|min:0.01',
        'transaction_type' => 'required|in:مصروف,تحصيل',
        'transaction_date' => 'required|date',
    ];

    public function mount($type)
    {
        $this->transaction_type = $type;
        $this->transaction_date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        Transaction::create([
            'account_id' => $this->account_id,
            'item_id' => $this->item_id,
            'amount' => $this->amount,
            'transaction_type' => $this->transaction_type,
            'transaction_date' => $this->transaction_date,
            'notes' => $this->notes,
        ]);

        session()->flash('message', 'تم حفظ الحركة بنجاح.');
        return redirect()->route('finance.index');
    }

    public function render()
    {
        return view('livewire.finance.transactions.create', [
            'accounts' => Account::all(),
            'items' => Item::all(),
        ]);
    }
}