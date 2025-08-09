<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use App\Models\Account;
use App\Models\Item;
use App\Models\Client;
use App\Models\Transaction;

class CreateCollection extends Component
{
    public $account_id;
    public $item_id;
    public $amount;
    public $notes;
    public $transaction_date;

    public $collection_type; // 'تحصل من عميل' | 'أخرى'
    public $client_id;       // عند "تحصل من عميل"
    public $clients = [];

    protected $listeners = ['closeModal'];

    protected function rules()
    {
        $rules = [
            'account_id'       => 'required|exists:accounts,id',
            'item_id'          => 'required|exists:items,id',
            'amount'           => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'notes'            => 'nullable|string|max:1000',
            'collection_type'  => 'nullable|in:تحصل من عميل,أخرى',
        ];

        if ($this->collection_type === 'تحصل من عميل') {
            $rules['client_id'] = 'required|exists:clients,id';
        }

        return $rules;
    }

    public function mount()
    {
        $this->transaction_date = now()->format('Y-m-d');
        if ($this->collection_type === 'تحصل من عميل') {
            $this->clients = Client::orderBy('name')->get();
        }
    }

    public function updatedCollectionType($value)
    {
        if ($value === 'تحصل من عميل') {
            $this->clients = Client::orderBy('name')->get();
        } else {
            $this->client_id = null;
            $this->clients = [];
        }
    }

    public function save()
    {
        $this->validate();

        Transaction::create([
            'account_id'       => $this->account_id,
            'item_id'          => $this->item_id,
            'type'             => 'تحصيل',
            'collection_type'  => $this->collection_type,
            'client_id'        => $this->collection_type === 'تحصل من عميل' ? $this->client_id : null,
            'amount'           => $this->amount,
            'transaction_date' => $this->transaction_date,
            'notes'            => $this->notes,
            'user_add'         => auth()->id(),
        ]);

        session()->flash('message', 'تم حفظ التحصيل بنجاح.');
         return redirect()->route('finance.transactions.index');

    }

    public function closeModal()
    {
        return redirect()->route('finance.transactions');
    }

    public function render()
    {
        return view('livewire.finance.transactions.create-collection', [
            'accounts' => Account::orderBy('name')->get(),
            'items'    => Item::orderBy('name')->get(),
            'clients'  => $this->clients,
        ]);
    }
}
