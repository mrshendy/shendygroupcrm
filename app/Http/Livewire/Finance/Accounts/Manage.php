<?php

namespace App\Http\Livewire\Finance\Accounts;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Account;
class Manage extends Component
{
use WithPagination;

public $name;
public $account_number;
public $type;
public $opening_balance;
public $bank;
public $is_main = false;

protected $paginationTheme = 'bootstrap'; // إضافة إذا كنت تستخدم Bootstrap للتبويب
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected $rules = [
        'name' => 'required|string',
        'account_number' => 'nullable|string',
        'type' => 'required|string',
        'opening_balance' => 'required|numeric',
        'bank' => 'nullable|string',
        'is_main' => 'boolean',
    ];

    public function save()
    {
        $this->validate();

        Account::create([
            'name' => $this->name,
            'account_number' => $this->account_number,
            'type' => $this->type,
            'opening_balance' => $this->opening_balance,
            'bank' => $this->bank,
            'is_main' => $this->is_main,
        ]);

        session()->flash('success', 'تم إضافة الحساب بنجاح');

        // إعادة تعيين الحقول
        $this->reset(['name', 'account_number', 'type', 'opening_balance', 'bank', 'is_main']);
    }

    public function render()
    {
       return view('livewire.finance.accounts.manage', [
    'accounts' => Account::latest()->paginate(10),
]);
    }
}
