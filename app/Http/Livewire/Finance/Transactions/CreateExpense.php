<?php

namespace App\Http\Livewire\Finance\Transactions;

use App\Models\Account;
use App\Models\Item;
use App\Models\Transaction;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Services\AccountService;

class CreateExpense extends Component
{
    public $from_account_id, $to_account_id;
    public $item_id, $amount, $transaction_date, $notes;

    public $searchFromAccount = '', $searchToAccount = '';
    public $fromAccounts = [], $toAccounts = [];
    public $items = [];

    protected $rules = [
        'from_account_id'  => 'required|exists:accounts,id|different:to_account_id',
        'to_account_id'    => 'required|exists:accounts,id|different:from_account_id',
        'item_id'          => 'required|exists:items,id',
        'amount'           => 'required|numeric|min:0.01',
        'transaction_date' => 'required|date',
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
        'amount.min'                => 'المبلغ يجب أن يكون أكبر من 0',

        'transaction_date.required' => 'يجب إدخال تاريخ العملية',
        'transaction_date.date'     => 'صيغة التاريخ غير صحيحة',

        'notes.string'              => 'الملاحظات يجب أن تكون نص',
        'notes.max'                 => 'الملاحظات يجب ألا تتجاوز 500 حرف',
    ];

    protected $accountService;

    public function boot(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function mount()
    {
        $this->items = Item::whereIn('type', ['مصروف','expense','expenses'])
            ->orderBy('name')
            ->get();

        $this->transaction_date = now()->format('Y-m-d');
        $this->fromAccounts = Account::orderBy('name')->get();
        $this->toAccounts   = Account::orderBy('name')->get();
    }

    public function save()
    {
        $this->validate(); // ✅ التحقق قبل الحفظ

        DB::transaction(function () {
            // حفظ العملية
            Transaction::create([
                'type'            => 'مصروفات',
                'from_account_id' => $this->from_account_id,
                'to_account_id'   => $this->to_account_id,
                'item_id'         => $this->item_id,
                'amount'          => $this->amount,
                'transaction_date'=> $this->transaction_date,
                'notes'           => $this->notes,
                'user_add'        => auth()->id(),
            ]);

            // تحديث أرصدة الحسابات
            $this->accountService->addExpense(
                $this->from_account_id,
                $this->amount,
                "خصم مصروف إلى حساب {$this->to_account_id}"
            );

            $this->accountService->addIncome(
                $this->to_account_id,
                $this->amount,
                "إضافة مصروف من حساب {$this->from_account_id}"
            );
        });

        session()->flash('success', '✅ تم حفظ المصروف وتحديث الأرصدة بنجاح');
        return redirect()->route('finance.transactions.index');
    }

    public function render()
    {
        $this->fromAccounts = Account::where('name', 'like', "%{$this->searchFromAccount}%")->get();
        $this->toAccounts   = Account::where('name', 'like', "%{$this->searchToAccount}%")->get();

        return view('livewire.finance.transactions.create-expense', [
            'items'        => $this->items,
            'fromAccounts' => $this->fromAccounts,
            'toAccounts'   => $this->toAccounts,
        ]);
    }
}
