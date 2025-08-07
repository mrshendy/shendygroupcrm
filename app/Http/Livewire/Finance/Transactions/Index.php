<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Item;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;


class Index extends Component
{
    use WithPagination;

    public $showModal = false;
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

    public function openCreateModal($type)
    {
        $this->resetInputs();
        $this->transaction_type = $type;
        $this->showModal = true;
    }
 protected $paginationTheme = 'bootstrap';

    public function resetInputs()
    {
        $this->reset(['account_id', 'item_id', 'amount', 'notes', 'transaction_date']);
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

    $this->dispatchBrowserEvent('transactionSaved'); // ← دا الحدث

    $this->resetInputs();
    $this->showModal = false;
}

    public function delete($id)
{
    $transaction = Transaction::findOrFail($id);
    $transaction->delete();

    $this->dispatchBrowserEvent('transactionDeleted');
}


    public function render()
    {
        $transactions = Transaction::with(['account', 'item'])
            ->latest()
            ->paginate(10);

        $accounts = Account::all();
        $items = Item::all();

        return view('livewire.finance.transactions.index', compact('transactions', 'accounts', 'items'));
    }
}
