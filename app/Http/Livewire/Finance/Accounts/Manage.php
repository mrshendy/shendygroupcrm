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
    public $notes;
    public $is_main = false;
    public $status = true;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

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

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();
        $data = $this->formData();

        if ($this->account_id) {
            // تعديل حساب
            Account::findOrFail($this->account_id)->update($data);
            session()->flash('message', 'تم تحديث الحساب بنجاح');
            $this->dispatchBrowserEvent('accountUpdated');
        } else {
            // إنشاء حساب جديد مع current_balance = opening_balance
            $data['current_balance'] = $this->opening_balance;
            Account::create($data);
            session()->flash('message', 'تم إضافة الحساب بنجاح');
            $this->dispatchBrowserEvent('accountAdded');
        }

        $this->resetInputs();
    }

    public function edit($id)
    {
        $acc = Account::findOrFail($id);

        $this->account_id      = $acc->id;
        $this->name            = $acc->name;
        $this->account_number  = $acc->account_number;
        $this->type            = $acc->type;
        $this->opening_balance = $acc->opening_balance;
        $this->bank            = $acc->bank;
        $this->notes           = $acc->notes;
        $this->is_main         = (bool) $acc->is_main;
        $this->status          = (bool) $acc->status;
    }

    public function delete($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        session()->flash('message', 'تم حذف الحساب بنجاح.');
        $this->dispatchBrowserEvent('accountDeleted');
    }

    public function resetInputs()
    {
        $this->reset([
            'account_id', 'name', 'account_number', 'type',
            'opening_balance', 'bank', 'notes', 'is_main', 'status',
        ]);
    }

    protected function formData()
    {
        return [
            'name'            => $this->name,
            'account_number'  => $this->account_number,
            'type'            => $this->type,
            'opening_balance' => $this->opening_balance,
            'bank'            => $this->bank,
            'is_main'         => $this->is_main ? 1 : 0,
            'status'          => $this->status ? 1 : 0,
            'notes'           => $this->notes,
        ];
    }

    public function render()
    {
        $accounts = Account::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('account_number', 'like', '%' . $this->search . '%')
                      ->orWhere('type', 'like', '%' . $this->search . '%')
                      ->orWhere('bank', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.finance.accounts.manage', compact('accounts'));
    }
}
