<?php

namespace App\Http\Livewire\Finance\Accounts;

use App\Models\Account;
use Livewire\Component;
use Livewire\WithPagination;

class Manage extends Component
{
    use WithPagination;

    public $account_id;

    public $name;

    public $account_number;

    public $type;

    public $opening_balance;

    public $bank;

    public $is_main = false;

    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name' => 'required|string',
        'account_number' => 'nullable|string|regex:/^[0-9]{8,24}$/', // من 8 إلى 24 رقم فقط,
        'type' => 'required|string',
        'opening_balance' => 'required|numeric',
        'bank' => 'nullable|string',
        'is_main' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if ($this->account_id) {
            $account = Account::findOrFail($this->account_id);
            $account->update([
                'name' => $this->name,
                'account_number' => $this->account_number,
                'type' => $this->type,
                'opening_balance' => $this->opening_balance,
                'bank' => $this->bank,
                'is_main' => $this->is_main,
            ]);
            session()->flash('message', 'تم تحديث بيانات الحساب بنجاح');
        } else {
            Account::create([
                'name' => $this->name,
                'account_number' => $this->account_number,
                'type' => $this->type,
                'opening_balance' => $this->opening_balance,
                'bank' => $this->bank,
                'is_main' => $this->is_main,
            ]);
            session()->flash('message', 'تم إضافة الحساب بنجاح');
        }

        $this->resetInputs();
    }

    public function edit($id)
    {
        $acc = Account::findOrFail($id);
        $this->account_id = $acc->id;
        $this->name = $acc->name;
        $this->account_number = $acc->account_number;
        $this->type = $acc->type;
        $this->opening_balance = $acc->opening_balance;
        $this->bank = $acc->bank;
        $this->is_main = $acc->is_main;
    }

    public function resetInputs()
    {
        $this->reset(['account_id', 'name', 'account_number', 'type', 'opening_balance', 'bank', 'is_main']);
    }

    public function render()
    {
        $accounts = Account::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('account_number', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.finance.accounts.manage', compact('accounts'));
    }
}
