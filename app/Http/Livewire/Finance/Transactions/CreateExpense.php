<?php

namespace App\Http\Livewire\Finance\Transactions;

use App\Models\Account;
use App\Models\Item;
use App\Models\Transaction;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CreateExpense extends Component
{
    public $from_account_id, $to_account_id;
    public $item_id, $amount, $transaction_date, $notes;

    public $searchFromAccount = '', $searchToAccount = '';
    public $fromAccounts = [], $toAccounts = [];
    public $items = [];

    protected $rules = [
        'from_account_id' => 'required|exists:accounts,id|different:to_account_id',
        'to_account_id'   => 'required|exists:accounts,id|different:from_account_id',
        'item_id'         => 'required|exists:items,id',
        'amount'          => 'required|numeric|min:0.01',
        'transaction_date'=> 'required|date',
        'notes'           => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->items = Item::whereIn('type', ['مصروف','expense','expenses'])->orderBy('name')->get();
        $this->transaction_date = now()->format('Y-m-d');
        $this->fromAccounts = Account::orderBy('name')->get();
        $this->toAccounts   = Account::orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            Transaction::create([
                'type' => 'مصروفات',
                'from_account_id' => $this->from_account_id,
                'to_account_id'   => $this->to_account_id,
                'item_id'         => $this->item_id,
                'amount'          => $this->amount,
                'transaction_date'=> $this->transaction_date,
                'notes'           => $this->notes,
                'user_add'        => auth()->id(),
            ]);

            Account::where('id',$this->from_account_id)->decrement('current_balance',$this->amount);
            Account::where('id',$this->to_account_id)->increment('current_balance',$this->amount);
        });

        session()->flash('message','✅ تم حفظ المصروف وتحديث الأرصدة بنجاح');
        return redirect()->route('finance.transactions.index');
    }

    public function render()
    {
        $this->fromAccounts = Account::where('name','like',"%{$this->searchFromAccount}%")->get();
        $this->toAccounts   = Account::where('name','like',"%{$this->searchToAccount}%")->get();

        return view('livewire.finance.transactions.create-expense', [
            'items' => $this->items,
            'fromAccounts' => $this->fromAccounts,
            'toAccounts' => $this->toAccounts,
        ]);
    }
}
