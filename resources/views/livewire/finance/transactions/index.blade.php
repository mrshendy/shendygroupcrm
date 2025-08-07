namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Item;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $transactions = Transaction::with(['account', 'item'])
            ->when($this->search, function ($query) {
                $query->whereHas('account', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('item', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.finance.transactions.index', compact('transactions'));
    }
}
