<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use App\Models\Account;
use App\Models\Client;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Services\AccountService;

class CreateCollection extends Component
{
    public $from_account_id, $to_account_id;
    public $item_id, $amount, $transaction_date, $collection_type, $client_id, $notes;

    public $accounts = [], $clients = [], $items = [];
    public $searchFromAccount = '', $searchToAccount = '';
    public $fromAccounts = [], $toAccounts = [];

    /** ✅ قواعد التحقق */
    protected $rules = [
        'from_account_id'  => 'required|exists:accounts,id|different:to_account_id',
        'to_account_id'    => 'required|exists:accounts,id|different:from_account_id',
        'item_id'          => 'required|exists:items,id',
        'amount'           => 'required|numeric|min:0.01',
        'transaction_date' => 'required|date',
        'collection_type'  => 'required|string|max:100',
        'client_id'        => 'nullable|exists:clients,id',
        'notes'            => 'nullable|string|max:500',
    ];

    /** ✅ رسائل التحقق بالعربي */
    protected $messages = [
        'from_account_id.required'  => 'يجب اختيار الحساب المصدر',
        'from_account_id.exists'    => 'الحساب المصدر غير موجود',
        'from_account_id.different' => 'الحساب المصدر يجب أن يختلف عن الحساب المستفيد',

        'to_account_id.required'    => 'يجب اختيار الحساب المستفيد',
        'to_account_id.exists'      => 'الحساب المستفيد غير موجود',
        'to_account_id.different'   => 'الحساب المستفيد يجب أن يختلف عن الحساب المصدر',

        'item_id.required'          => 'يجب اختيار البند المالي',
        'item_id.exists'            => 'البند المحدد غير موجود',

        'amount.required'           => 'يجب إدخال مبلغ العملية',
        'amount.numeric'            => 'المبلغ يجب أن يكون رقمًا',
        'amount.min'                => 'المبلغ يجب أن يكون أكبر من صفر',

        'transaction_date.required' => 'يجب إدخال تاريخ العملية',
        'transaction_date.date'     => 'صيغة التاريخ غير صحيحة',

        'collection_type.required'  => 'يجب تحديد نوع التحصيل',
        'collection_type.string'    => 'نوع التحصيل يجب أن يكون نصًا',
        'collection_type.max'       => 'نوع التحصيل يجب ألا يتجاوز 100 حرف',

        'client_id.required'        => 'يجب اختيار العميل عند تحديد "تحصيل من عميل"',
        'client_id.exists'          => 'العميل المحدد غير موجود',

        'notes.string'              => 'الملاحظات يجب أن تكون نصًا',
        'notes.max'                 => 'الملاحظات يجب ألا تتجاوز 500 حرف',
    ];

    protected $accountService;

    public function boot(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function mount()
    {
        $this->items   = Item::whereIn('type', ['إيراد','دخل','income','receipt'])
            ->orderBy('name')
            ->get();

        $this->clients = Client::orderBy('name')->get();
        $this->transaction_date = now()->format('Y-m-d');
        $this->fromAccounts = Account::orderBy('name')->get();
        $this->toAccounts   = Account::orderBy('name')->get();
    }

    public function save()
    {
        $rules = $this->rules;

        // ✅ تحقق إضافي: لو نوع التحصيل "تحصيل من عميل"
        if ($this->collection_type === 'تحصيل من عميل') {
            $rules['client_id'] = 'required|exists:clients,id';
        }

        $this->validate($rules);

        DB::transaction(function () {
            // إنشاء العملية
            Transaction::create([
                'type'            => 'تحصيل',
                'from_account_id' => $this->from_account_id,
                'to_account_id'   => $this->to_account_id,
                'item_id'         => $this->item_id,
                'amount'          => $this->amount,
                'transaction_date'=> $this->transaction_date,
                'collection_type' => $this->collection_type,
                'client_id'       => $this->client_id,
                'notes'           => $this->notes,
                'user_add'        => auth()->id(),
            ]);

            // تحديث الأرصدة
            $this->accountService->addExpense(
                $this->from_account_id,
                $this->amount,
                "خصم بسبب التحصيل (إلى حساب {$this->to_account_id})"
            );

            $this->accountService->addIncome(
                $this->to_account_id,
                $this->amount,
                "إيداع من حساب {$this->from_account_id}"
            );
        });

        session()->flash('success','✅ تم حفظ التحصيل وتحديث الأرصدة بنجاح');
        return redirect()->route('finance.transactions.index');
    }

    public function render()
    {
        $this->fromAccounts = Account::where('name','like',"%{$this->searchFromAccount}%")->get();
        $this->toAccounts   = Account::where('name','like',"%{$this->searchToAccount}%")->get();

        return view('livewire.finance.transactions.create-collection', [
            'items'        => $this->items,
            'fromAccounts' => $this->fromAccounts,
            'toAccounts'   => $this->toAccounts,
            'clients'      => $this->clients,
        ]);
    }
}
