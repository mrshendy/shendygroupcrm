<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use App\Models\Account;
use App\Models\Client;
use App\Models\Item;
use App\Models\Transaction;

class CreateCollection extends Component
{
    public $from_account_id, $to_account_id;
    public $item_id, $amount, $transaction_date, $collection_type, $client_id, $notes;

    public $accounts = [], $clients = [], $items = [];

    protected $rules = [
        'from_account_id'  => 'required|exists:accounts,id|different:to_account_id',
        'to_account_id'    => 'required|exists:accounts,id|different:from_account_id',
        'item_id'          => 'required|exists:items,id',
        'amount'           => 'required|numeric|min:0.01',
        'transaction_date' => 'required|date',
        'collection_type'  => 'nullable|string|max:100',
        'client_id'        => 'nullable|exists:clients,id',
        'notes'            => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->accounts = Account::orderBy('name')->get();
        $this->clients  = \App\Models\Client::orderBy('name')->get();

        // بنود التحصيل/الدخل فقط – عمود type
        $this->items = Item::whereIn('type', ['تحصيل','دخل','income','receipt'])
            ->orderBy('name')->get();

        $this->transaction_date = now()->format('Y-m-d');
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->collection_type === 'تحصل من عميل') {
            $rules['client_id'] = 'required|exists:clients,id';
        }
        $this->validate($rules);

        Transaction::create([
            'transaction_type' => 'تحصيل',
            'from_account_id'  => $this->from_account_id,
            'to_account_id'    => $this->to_account_id,
            'item_id'          => $this->item_id,
            'amount'           => $this->amount,
            'transaction_date' => $this->transaction_date,
            'collection_type'  => $this->collection_type,
            'client_id'        => $this->client_id,
            'notes'            => $this->notes,
        ]);

        session()->flash('message','تم حفظ التحصيل بنجاح');
        return redirect()->route('finance.transactions.index');
    }

    public function render()
    {
        return view('livewire.finance.transactions.create-collection');
    }
}
