<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;

class TransactionPageController extends Controller
{
    public function createExpense()
    {
        return view('finance.transactions.create-expense');
    }

    public function createIncome()
    {
        return view('finance.transactions.create-income');
    }
}
