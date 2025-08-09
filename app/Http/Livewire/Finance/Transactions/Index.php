<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Item;
use App\Models\Client;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $showModal = false;

    // حقول العملية
    public $account_id, $item_id, $amount, $notes, $transaction_date, $transaction_type;

    // حقول التحصيل
    public $collection_type; // 'تحصل من عميل' | 'أخرى'
    public $client_id;       // عميل التحصيل (لو "تحصل من عميل")

    protected function rules()
    {
        $rules = [
            'account_id'       => 'required|exists:accounts,id',
            'item_id'          => 'required|exists:items,id',
            'amount'           => 'required|numeric|min:0.01',
            'transaction_type' => 'required|in:مصروفات,تحصيل',
            'transaction_date' => 'required|date',
            'notes'            => 'nullable|string',
            // عند التحصيل فقط
            'collection_type'  => 'nullable|in:تحصل من عميل,أخرى',
        ];

        if ($this->transaction_type === 'تحصيل' && $this->collection_type === 'تحصل من عميل') {
            $rules['client_id'] = 'required|exists:clients,id';
        }

        return $rules;
    }

    public function mount()
    {
        $this->transaction_date = now()->format('Y-m-d');
    }

    public function openCreateModal($type)
    {
        // $type = 'مصروفات' أو 'تحصيل'
        $this->reset(['account_id','item_id','amount','notes','transaction_date','transaction_type','collection_type','client_id']);
        $this->resetValidation();

        $this->transaction_type = $type;
        $this->transaction_date = now()->format('Y-m-d');

        // لو مصروفات امسح حقول التحصيل
        if ($type === 'مصروفات') {
            $this->collection_type = null;
            $this->client_id = null;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        Transaction::create([
            'account_id'       => $this->account_id,
            'item_id'          => $this->item_id,
            'amount'           => $this->amount,
            'transaction_type' => $this->transaction_type,   // 'مصروفات' أو 'تحصيل'
            'transaction_date' => $this->transaction_date,
            'notes'            => $this->notes,
            // خزّن نوع التحصيل والعميل فقط عندما النوع = تحصيل
            'collection_type'  => $this->transaction_type === 'تحصيل' ? $this->collection_type : null,
            'client_id'        => ($this->transaction_type === 'تحصيل' && $this->collection_type === 'تحصل من عميل')
                                    ? $this->client_id
                                    : null,
        ]);

        $this->resetPage();
        $this->dispatchBrowserEvent('transactionSaved');

        $this->reset(['account_id','item_id','amount','notes','transaction_date','transaction_type','collection_type','client_id']);
        $this->showModal = false;
    }

    public function delete($id)
    {
        $trx = Transaction::findOrFail($id);
        $trx->delete();

        $this->resetPage();
        $this->dispatchBrowserEvent('transactionDeleted');
    }

    public function render()
    {
        $transactions = Transaction::with(['account','item','client']) // مهم وجود client
            ->latest()
            ->paginate(10);

        $accounts = Account::orderBy('name')->get();
        $items    = Item::orderBy('name')->get();
        $clients  = Client::orderBy('name')->get();

        return view('livewire.finance.transactions.index', compact('transactions','accounts','items','clients'));
    }
}
