<?php

namespace App\Http\Livewire\Finance\Transactions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch(){ $this->resetPage(); }

    public function delete($id)
    {
        if ($tx = Transaction::find($id)) {
            $tx->delete();
            session()->flash('message','تم حذف الحركة بنجاح');
        }
    }

    public function render()
    {
        $transactions = Transaction::with(['fromAccount','toAccount','item','client'])
            ->when($this->search, function($q){
                $q->where('notes','like',"%{$this->search}%")
                  ->orWhere('collection_type','like',"%{$this->search}%");
            })
            ->latest()->paginate(10);

        return view('livewire.finance.transactions.index', compact('transactions'));
    }
}
