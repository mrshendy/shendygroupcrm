<?php

namespace App\Http\Livewire\Finance\Accounts;

use Livewire\Component;
use App\Models\Account;

class Edit extends Component
{
    public $account_id;
    public $name;
    public $account_number;
    public $type;
    public $opening_balance;
    public $bank;
    public $notes;
    public $is_main = false;
    public $status = true;

    protected $rules = [
        'name'            => 'required|string|min:3|max:100',
        'account_number'  => 'nullable|string|regex:/^[\d\-\s]+$/|max:30',
        'type'            => 'required|in:bank,cash,wallet,investment,instapay',
        'opening_balance' => 'required|numeric|min:0|max:1000000000',
        'bank'            => 'nullable|string|min:2|max:100',
        'is_main'         => 'boolean',
        'status'          => 'boolean',
        'notes'           => 'nullable|string|max:500',
    ];

    public function mount($id)
    {
        $account = Account::findOrFail($id);

        $this->account_id      = $account->id;
        $this->name            = $account->name;
        $this->account_number  = $account->account_number;
        $this->type            = $account->type;
        $this->opening_balance = $account->opening_balance;
        $this->bank            = $account->bank;
        $this->notes           = $account->notes;
        $this->is_main         = (bool) $account->is_main;
        $this->status          = (bool) $account->status;
    }

    public function update()
    {
        $this->validate();

        $account = Account::findOrFail($this->account_id);

        $account->update([
            'name'            => $this->name,
            'account_number'  => $this->account_number,
            'type'            => $this->type,
            'opening_balance' => $this->opening_balance,
            'bank'            => $this->bank,
            'notes'           => $this->notes,
            'is_main'         => $this->is_main ? 1 : 0,
            'status'          => $this->status ? 1 : 0,
        ]);

        session()->flash('success', '✅ تم تحديث الحساب بنجاح');
        return redirect()->route('finance.accounts.manage'); // رجوع لصفحة الإدارة
    }

    public function render()
    {
        return view('livewire.finance.accounts.edit')
            ->layout('layouts.app'); // layout الأساسي
    }
}
