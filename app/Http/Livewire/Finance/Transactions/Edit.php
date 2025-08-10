<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Item;

class Edit extends Component
{
    public $transactionId;
    public $amount,$transaction_date,$from_account_id,$to_account_id,$item_id,$collection_type,$notes;
    public $transaction_type;
    public $accounts = [], $items = [];

    protected $rules = [
        'from_account_id'  => 'required|exists:accounts,id|different:to_account_id',
        'to_account_id'    => 'required|exists:accounts,id|different:from_account_id',
        'item_id'          => 'required|exists:items,id',
        'amount'           => 'required|numeric|min:0.01',
        'transaction_date' => 'required|date',
        'collection_type'  => 'nullable|string|max:100',
        'notes'            => 'nullable|string|max:500',
    ];

    public function mount($transactionId)
    {
        $t = Transaction::findOrFail($transactionId);

        $this->transactionId   = $t->id;
        $this->amount          = $t->amount;
        $this->transaction_date= $t->transaction_date ? \Carbon\Carbon::parse($t->transaction_date)->format('Y-m-d') : null;
        $this->from_account_id = $t->from_account_id;
        $this->to_account_id   = $t->to_account_id;
        $this->item_id         = $t->item_id;
        $this->collection_type = $t->collection_type;
        $this->notes           = $t->notes;
        $this->transaction_type= $t->transaction_type;

        $this->accounts = Account::orderBy('name')->get();
        $this->items    = $this->itemsForType($this->transaction_type);
    }

    private function itemsForType($type)
    {
        if (in_array($type, ['مصروفات','expense','expenses'])) {
            return Item::whereIn('type',['مصروفات','expense','expenses'])->orderBy('name')->get();
        }
        return Item::whereIn('type',['تحصيل','دخل','income','receipt'])->orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();

        Transaction::findOrFail($this->transactionId)->update([
            'amount'           => $this->amount,
            'transaction_date' => $this->transaction_date,
            'from_account_id'  => $this->from_account_id,
            'to_account_id'    => $this->to_account_id,
            'item_id'          => $this->item_id,
            'collection_type'  => $this->collection_type,
            'notes'            => $this->notes,
        ]);

        session()->flash('message','تم حفظ التعديلات بنجاح');
        return redirect()->route('finance.transactions.index');
    }

    public function render()
    {
        return view('livewire.finance.transactions.edit');
    }
}
