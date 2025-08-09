<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use App\Models\Account;
use App\Models\Item;
use App\Models\Transaction;

class CreateExpense extends Component
{
    public $account_id;
    public $item_id;
    public $amount;
    public $notes;
    public $transaction_date;

    protected $listeners = ['closeModal'];

    protected function rules()
    {
        return [
            'account_id'       => 'required|exists:accounts,id',
            'item_id'          => 'required|exists:items,id',
            'amount'           => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'notes'            => 'nullable|string|max:1000',
        ];
    }

    public function mount()
    {
        $this->transaction_date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        Transaction::create([
            'account_id'       => $this->account_id,
            'item_id'          => $this->item_id,
            'type'             => 'مصروفات',
            'amount'           => $this->amount,
            'transaction_date' => $this->transaction_date,
            'notes'            => $this->notes,
            'user_add'         => auth()->id(),
        ]);

        session()->flash('message', 'تم حفظ المصروف بنجاح.');
        // وجّه كما تحب: لفهرس الحركات أو نفس الصفحة
        return redirect()->route('finance.transactions.index');
    }

    public function closeModal()
    {
        return redirect()->route('finance.transactions.index');
    }

    public function render()
    {
        return view('livewire.finance.transactions.create-expense', [
            'accounts' => Account::orderBy('name')->get(),
            'items'    => Item::orderBy('name')->get(),
        ]);
    }
}
