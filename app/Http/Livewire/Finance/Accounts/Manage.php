<?php

namespace App\Http\Livewire\Finance\Accounts;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Account;

class Manage extends Component
{
    use WithPagination;

    public $account_id;
    public $name;
    public $account_number;
    public $type;
    public $opening_balance;
    public $bank;
    public $notes;
    public $is_main = false;
    public $status = true;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name' => 'required|string|min:3|max:100',
        'account_number' => 'nullable|string|regex:/^[\d\-\s]+$/|max:30',
        'type' => 'required|in:بنكي,نقدي,إلكتروني,استثمار',
        'opening_balance' => 'required|numeric|min:0|max:1000000000',
        'bank' => 'nullable|string|min:2|max:100',
        'is_main' => 'boolean',
        'status' => 'boolean',
        'notes' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'name.required' => 'الرجاء إدخال اسم الحساب.',
        'name.min' => 'اسم الحساب يجب أن يحتوي على 3 أحرف على الأقل.',
        'name.max' => 'اسم الحساب لا يجب أن يتجاوز 100 حرفاً.',

        'account_number.string' => 'رقم الحساب يجب أن يكون نصاً.',
        'account_number.regex' => 'رقم الحساب يجب أن يحتوي فقط على أرقام، شرطات، أو مسافات.',
        'account_number.max' => 'رقم الحساب لا يجب أن يتجاوز 30 حرفاً.',

        'type.required' => 'نوع الحساب مطلوب.',
        'type.in' => 'الرجاء اختيار نوع حساب صحيح (بنكي، نقدي، إلكتروني، أو استثمار).',

        'opening_balance.required' => 'الرجاء إدخال الرصيد الافتتاحي.',
        'opening_balance.numeric' => 'يجب أن يكون الرصيد رقمياً.',
        'opening_balance.min' => 'الحد الأدنى للرصيد هو 0.',
        'opening_balance.max' => 'الحد الأقصى للرصيد هو 1,000,000,000.',

        'bank.string' => 'اسم البنك يجب أن يكون نصاً.',
        'bank.min' => 'اسم البنك يجب أن يحتوي على حرفين على الأقل.',
        'bank.max' => 'اسم البنك لا يجب أن يتجاوز 100 حرف.',

        'notes.string' => 'الملاحظات يجب أن تكون نصاً.',
        'notes.max' => 'الملاحظات لا يجب أن تتجاوز 500 حرف.',

        'is_main.boolean' => 'قيمة الحقل "حساب رئيسي" يجب أن تكون صحيحة (صح أو خطأ).',
        'status.boolean' => 'قيمة الحقل "الحالة" يجب أن تكون صحيحة (صح أو خطأ).',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->render();
    }

    public function save()
    {
        $this->validate();

        if ($this->account_id) {
            $account = Account::findOrFail($this->account_id);
            $account->update($this->formData());
            session()->flash('message', 'تم تحديث الحساب بنجاح');
        } else {
            Account::create($this->formData());
            session()->flash('message', 'تم إضافة الحساب بنجاح');
        }

        $this->resetInputs();
    }

    public function edit($id)
    {
        $acc = Account::find($id);

        if (!$acc) {
            session()->flash('message', 'الحساب غير موجود.');
            return;
        }

        $this->account_id = $acc->id;
        $this->name = $acc->name;
        $this->account_number = $acc->account_number;
        $this->type = $acc->type;
        $this->opening_balance = $acc->opening_balance;
        $this->bank = $acc->bank;
        $this->notes = $acc->notes;
        $this->is_main = (bool) $acc->is_main;
        $this->status = (bool) $acc->status;
    }

    public function resetInputs()
    {
        $this->reset([
            'account_id',
            'name',
            'account_number',
            'type',
            'opening_balance',
            'bank',
            'is_main',
            'status',
            'notes',
        ]);
    }

    protected function formData()
    {
        return [
            'name' => $this->name,
            'account_number' => $this->account_number,
            'type' => $this->type,
            'opening_balance' => $this->opening_balance,
            'bank' => $this->bank,
            'is_main' => $this->is_main ? 1 : 0,
            'status' => $this->status ? 1 : 0,
            'notes' => $this->notes,
        ];
    }

    public function render()
    {
        $accounts = Account::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('account_number', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.finance.accounts.manage', [
            'accounts' => $accounts
        ]);
    }
}
