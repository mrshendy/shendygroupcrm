<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use App\Models\Account;
use App\Models\Client;
use App\Models\Item;
use App\Models\Transaction;

class Create extends Component
{
    public $account_id;
    public $item_id;
    public $amount;
    public $notes;
    public $transaction_date;
    public $transaction_type;   // 'مصروفات' أو 'تحصيل'
    public $collection_type;    // 'تحصل من عميل' | 'أخرى'
    public $client_id;          // عند "تحصل من عميل"
    public $clients = [];       // قائمة العملاء عند "تحصل من عميل"

    protected $listeners = ['closeModal'];

    protected function rules()
    {
        $rules = [
            'account_id'       => 'required|exists:accounts,id',
            'item_id'          => 'required|exists:items,id',
            'amount'           => 'required|numeric|min:0.01',
            'transaction_type' => 'required|string|in:مصروفات,تحصيل',
            'transaction_date' => 'required|date',
            'notes'            => 'nullable|string|max:1000',
            'collection_type'  => 'nullable|in:تحصل من عميل,أخرى',
        ];

        if ($this->transaction_type === 'تحصيل' && $this->collection_type === 'تحصل من عميل') {
            $rules['client_id'] = 'required|exists:clients,id';
        }

        return $rules;
    }

    public function mount($type)
    {
        $this->transaction_type = $type;
        $this->transaction_date = now()->format('Y-m-d');

        if ($this->transaction_type === 'تحصيل' && $this->collection_type === 'تحصل من عميل') {
            $this->clients = Client::orderBy('name')->get();
        }
    }

    public function updatedCollectionType($value)
    {
        if ($this->transaction_type === 'تحصيل' && $value === 'تحصل من عميل') {
            $this->clients = Client::orderBy('name')->get();
        } else {
            $this->client_id = null;
            $this->clients   = [];
        }
    }

    public function updatedTransactionType($value)
    {
        if ($value === 'مصروفات') {
            $this->collection_type = null;
            $this->client_id       = null;
            $this->clients         = [];
        }
    }

    public function save()
    {
        $this->validate();

        $transaction = Transaction::create([
            'from_account_id'  => $this->transaction_type === 'مصروفات' ? $this->account_id : null,
            'to_account_id'    => $this->transaction_type === 'تحصيل' ? $this->account_id : null,
            'item_id'          => $this->item_id,
            'amount'           => $this->amount,
            'transaction_type' => $this->transaction_type,
            'transaction_date' => $this->transaction_date,
            'notes'            => $this->notes,
            'collection_type'  => $this->transaction_type === 'تحصيل' ? $this->collection_type : null,
            'client_id'        => ($this->transaction_type === 'تحصيل' && $this->collection_type === 'تحصل من عميل')
                                    ? $this->client_id
                                    : null,
            'user_add'         => auth()->id(),
        ]);

        // تحديث الرصيد
        $account = Account::find($this->account_id);
        if ($account) {
            if ($this->transaction_type === 'مصروفات') {
                $account->current_balance -= $this->amount;
            } elseif ($this->transaction_type === 'تحصيل') {
                $account->current_balance += $this->amount;
            }
            $account->save();
        }

        session()->flash('message', 'تم حفظ الحركة وتحديث الرصيد بنجاح.');
        return redirect()->route('finance.transactions.index');
    }

    public function closeModal()
    {
        return redirect()->route('finance.transactions.index');
    }

    public function render()
    {
        return view('livewire.finance.transactions.create', [
            'accounts' => Account::orderBy('name')->get(),
            'items'    => Item::orderBy('name')->get(),
            'clients'  => $this->clients,
        ]);
    }
}
