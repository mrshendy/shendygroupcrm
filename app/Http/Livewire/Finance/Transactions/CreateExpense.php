<?php

namespace App\Http\Livewire\Finance\Transactions;

use App\Models\Account;
use App\Models\Item;
use App\Models\Transaction;
use Livewire\Component;

class CreateExpense extends Component
{
    public $from_account_id;

    public $to_account_id;

    public $item_id;

    public $amount;

    public $transaction_date;

    public $notes;

    public $accounts = [];

    public $items = [];

    public $searchFromAccount = '';

    public $searchToAccount = '';

    public $fromAccounts = [];

    public $toAccounts = [];

    protected $rules = [
        'from_account_id' => 'required|exists:accounts,id|different:to_account_id',
        'to_account_id' => 'required|exists:accounts,id|different:from_account_id',
        'item_id' => 'required|exists:items,id',
        'amount' => 'required|numeric|min:0.01',
        'transaction_date' => 'required|date',
        'notes' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->accounts = Account::orderBy('name')->get();
        // بنود المصروفات فقط
        $this->items = Item::whereIn('type', ['مصروف', 'expense', 'expenses'])
            ->orderBy('name')->get();

        $this->transaction_date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        // حفظ العملية
        $transaction = Transaction::create([
            'type' => 'مصروفات',
            'from_account_id' => $this->from_account_id,
            'to_account_id' => $this->to_account_id,
            'item_id' => $this->item_id,
            'amount' => $this->amount,
            'transaction_date' => $this->transaction_date,
            'notes' => $this->notes,
            'collection_type' => 'expense',
            'user_add' => auth()->id(),
        ]);

        // تحديث الأرصدة
        $from = Account::find($this->from_account_id);
        $to = Account::find($this->to_account_id);

        if ($from) {
            $from->current_balance -= $this->amount;
            $from->save();
        }

        if ($to) {
            $to->current_balance += $this->amount;
            $to->save();
        }

        session()->flash('message', 'تم حفظ المصروف وتحديث الأرصدة بنجاح');

        return redirect()->route('finance.transactions.index');
    }

    public function render()
    {
        $this->fromAccounts = Account::where('name', 'like', "%{$this->searchFromAccount}%")
            ->orderBy('name')->get();

        $this->toAccounts = Account::where('name', 'like', "%{$this->searchToAccount}%")
            ->orderBy('name')->get();

        return view('livewire.finance.transactions.create-expense', [
            'items' => $this->items,
            'fromAccounts' => $this->fromAccounts,
            'toAccounts' => $this->toAccounts,
        ]);
    }
}
