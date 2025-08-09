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

    // لا نحتاج أي حقول إنشاء هنا؛ الصفحة للعرض فقط

    public function delete($id)
    {
        $trx = Transaction::findOrFail($id);
        $trx->delete(); // Soft delete كما في المايجريشن

        // لإعادة تحميل الصفحة على نفس رقم الصفحة بعد الحذف (مع ضبط الفهرسة)
        if ($this->page > 1 && $this->page > $this->paginator()->lastPage()) {
            $this->page = $this->paginator()->lastPage();
        }

        $this->dispatchBrowserEvent('transactionDeleted');
    }

    public function render()
    {
        $transactions = Transaction::with(['account','item','client'])
            ->latest()
            ->paginate(10);

        // الأزرار أعلى الجدول تذهب لصفحات الإنشاء المنفصلة
        return view('livewire.finance.transactions.index', [
            'transactions' => $transactions,
            'accounts'     => Account::orderBy('name')->get(),
            'items'        => Item::orderBy('name')->get(),
            'clients'      => Client::orderBy('name')->get(),
        ]);
    }

    // مساعد داخلي لجلب الـpaginator الحالي
    protected function paginator()
    {
        return Transaction::paginate(10);
    }
}
