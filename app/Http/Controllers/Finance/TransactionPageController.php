<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionPageController extends Controller
{
    public function index()
    {
        // صفحة القائمة (هتحط فيها @livewire('finance.transactions.index') أو جدولك)
        return view('finance.transactions.index');
    }

    public function createIncome()
    {
        $transactions = Transaction::with(['account','item','client'])
            ->latest()
            ->paginate(10);

        return view('finance.transactions.create-income', compact('transactions'));
    }

    public function createExpense()
    {
        $transactions = Transaction::with(['account','item','client'])
            ->latest()
            ->paginate(10);

        return view('finance.transactions.create-expense', compact('transactions'));
    }
}
