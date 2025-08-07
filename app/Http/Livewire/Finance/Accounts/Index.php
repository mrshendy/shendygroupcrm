<?php
namespace App\Http\Livewire\Finance\Accounts;

use Livewire\Component;
use App\Models\Account;
use Livewire\WithPagination;

class Index extends Component
{
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

    public function render()
    {
        $accounts = Account::where('name', 'like', "%{$this->search}%")
            ->orWhere('type', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

return view('livewire.finance.index', compact('accounts'));
    }
}