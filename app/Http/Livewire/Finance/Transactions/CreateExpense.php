<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use App\Models\Account;
use App\Models\Item;
use App\Models\Transaction;

class CreateExpense extends Component
{
    public $from_account_id, $to_account_id;
    public $item_id, $amount, $transaction_date, $notes;

    public $accounts = [];
    public $items    = [];

    protected $rules = [
        'from_account_id'  => 'required|exists:accounts,id|different:to_account_id',
        'to_account_id'    => 'required|exists:accounts,id|different:from_account_id',
        'item_id'          => 'required|exists:items,id',
        'amount'           => 'required|numeric|min:0.01',
        'transaction_date' => 'required|date',
        'notes'            => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->accounts = Account::orderBy('name')->get();
        // بنود المصروفات فقط – عمود type
        $this->items = Item::whereIn('type', ['مصروف','expense','expenses'])
            ->orderBy('name')->get();
        $this->transaction_date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        Transaction::create([
            'type' => 'مصروفات',
            'from_account_id'  => $this->from_account_id,
            'to_account_id'    => $this->to_account_id,
            'item_id'          => $this->item_id,
            'amount'           => $this->amount,
            'transaction_date' => $this->transaction_date,
            'notes'            => $this->notes,
            'collection_type'  => 'expense', // نوع الحركة
            'user_add'          => auth()->id(),

        ]);

        session()->flash('message','تم حفظ المصروف بنجاح');
        return redirect()->route('finance.transactions.index');
    }

    public function render()
    {
        return view('livewire.finance.transactions.create-expense');
    }
}
