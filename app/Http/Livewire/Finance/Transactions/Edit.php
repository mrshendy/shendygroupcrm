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
    public $type;
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

        $this->transactionId    = $t->id;
        $this->amount           = $t->amount;
        $this->transaction_date = $t->transaction_date ? \Carbon\Carbon::parse($t->transaction_date)->format('Y-m-d') : null;
        $this->from_account_id  = $t->from_account_id;
        $this->to_account_id    = $t->to_account_id;
        $this->item_id          = $t->item_id;
        $this->collection_type  = $t->collection_type;
        $this->notes            = $t->notes;
        $this->type             = $t->type;

        $this->accounts = Account::orderBy('name')->get();
        $this->items    = $this->itemsForType($this->type);
    }

    private function itemsForType($type)
    {
        if (in_array($type, ['مصروف','expense','expenses'])) {
            return Item::whereIn('type',['مصروف','expense','expenses'])->orderBy('name')->get();
        }
        return Item::whereIn('type',['إيراد','دخل','income','receipt'])->orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();

        $tx = Transaction::findOrFail($this->transactionId);

        // 1) إلغاء تأثير العملية القديمة
        if ($tx->from_account_id) {
            $from = Account::find($tx->from_account_id);
            if ($from) {
                $from->current_balance += $tx->amount;
                $from->save();
            }
        }

        if ($tx->to_account_id) {
            $to = Account::find($tx->to_account_id);
            if ($to) {
                $to->current_balance -= $tx->amount;
                $to->save();
            }
        }

        // 2) تحديث بيانات العملية
        $tx->update([
            'amount'           => $this->amount,
            'transaction_date' => $this->transaction_date,
            'from_account_id'  => $this->from_account_id,
            'to_account_id'    => $this->to_account_id,
            'item_id'          => $this->item_id,
            'collection_type'  => $this->collection_type,
            'notes'            => $this->notes,
            'user_add'         => auth()->id(),
        ]);

        // 3) تطبيق تأثير العملية الجديدة
        if ($this->from_account_id) {
            $from = Account::find($this->from_account_id);
            if ($from) {
                $from->current_balance -= $this->amount;
                $from->save();
            }
        }

        if ($this->to_account_id) {
            $to = Account::find($this->to_account_id);
            if ($to) {
                $to->current_balance += $this->amount;
                $to->save();
            }
        }

        session()->flash('message','تم حفظ التعديلات وتحديث الأرصدة بنجاح');
        return redirect()->route('finance.transactions.index');
    }

    public function render()
    {
        return view('livewire.finance.transactions.edit');
    }
}
