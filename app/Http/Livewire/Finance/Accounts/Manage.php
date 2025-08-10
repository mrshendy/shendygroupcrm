<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Item;
use Illuminate\Support\Carbon;

class Table extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // حقول التعديل
    public $transaction_id;
    public $amount;
    public $transaction_date;
    public $from_account_id;
    public $to_account_id;
    public $item_id;
    public $collection_type;
    public $notes;

    // بيانات مساعدة
    public $accounts = [];
    public $items = [];
    public $search = '';
    public $perPage = 10;

    protected $rules = [
        'amount'           => 'required|numeric|min:0.01',
        'transaction_date' => 'required|date',
        'from_account_id'  => 'nullable|exists:accounts,id',
        'to_account_id'    => 'nullable|exists:accounts,id',
        'item_id'          => 'nullable|exists:items,id',
        'collection_type'  => 'nullable|string|max:100',
        'notes'            => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->accounts = Account::orderBy('name')->get();
        $this->items    = Item::orderBy('name')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /** فتح مودال التعديل وملء الحقول */
    public function edit($id)
    {
        $t = Transaction::with(['fromAccount','toAccount','item','client'])->findOrFail($id);

        $this->transaction_id   = $t->id;
        $this->amount           = $t->amount;
        $this->transaction_date = $t->transaction_date
            ? Carbon::parse($t->transaction_date)->format('Y-m-d')
            : null;
        $this->from_account_id  = $t->from_account_id;
        $this->to_account_id    = $t->to_account_id;
        $this->item_id          = $t->item_id;
        $this->collection_type  = $t->collection_type;
        $this->notes            = $t->notes;

        $this->dispatchBrowserEvent('openEditModal');
    }

    /** حفظ التعديلات */
    public function save()
    {
        $this->validate();

        $t = Transaction::findOrFail($this->transaction_id);

        $t->update([
            'amount'           => $this->amount,
            'transaction_date' => $this->transaction_date,
            'from_account_id'  => $this->from_account_id,
            'to_account_id'    => $this->to_account_id,
            'item_id'          => $this->item_id,
            'collection_type'  => $this->collection_type,
            'notes'            => $this->notes,
        ]);

        $this->dispatchBrowserEvent('editSaved');
        $this->resetForm();
        $this->resetPage(); // عشان لو اتغير الترتيب بعد التعديل
    }

    /** حذف ريكورد */
    public function delete($id)
    {
        Transaction::findOrFail($id)->delete();
        $this->dispatchBrowserEvent('rowDeleted');
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset([
            'transaction_id','amount','transaction_date',
            'from_account_id','to_account_id','item_id',
            'collection_type','notes'
        ]);
    }

    public function render()
    {
        $transactions = Transaction::with(['fromAccount','toAccount','item','client'])
            ->when($this->search, function ($q) {
                $q->where(function($qq){
                    $qq->where('notes','like','%'.$this->search.'%')
                       ->orWhere('collection_type','like','%'.$this->search.'%');
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.finance.transactions.table', compact('transactions'));
    }
}
