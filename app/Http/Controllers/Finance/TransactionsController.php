<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;

class TransactionsController extends Controller
{
     function __construct(){
        $this->middleware('permission:finance-list|finance-create|finance-edit|finance-delete', ['only' => ['index','store']]);
        $this->middleware('permission:finance-create', ['only' => ['create','store']]);
        $this->middleware('permission:finance-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:finance-delete', ['only' => ['destroy']]);
    }
    /** صفحة القائمة (تستضيف <livewire:finance.transactions.index />) */
    public function index()
    {
        return view('finance.transactions.index');
    }

    /** صفحة إضافة مصروف (تستضيف <livewire:finance.transactions.create-expense />) */
    public function createExpense()
    {
        return view('finance.transactions.create-expense');
    }

    /** صفحة إضافة تحصيل (تستضيف <livewire:finance.transactions.create-collection /> أو create-income) */
    public function createIncome()
    {
        if (view()->exists('finance.transactions.create-collection')) {
            return view('finance.transactions.create-collection');
        }
        return view('finance.transactions.create-collection', [
            'collectionType' => 'income', // For backward compatibility
        ]);
    }
    public function show($transactionId){
        return view('finance.transactions.show', compact('transactionId'));
    }

    /** صفحة التعديل (تستضيف <livewire:finance.transactions.edit :transactionId="$transactionId" />) */
    public function edit($transactionId)
    {
        return view('finance.transactions.edit', compact('transactionId'));
    }
    public function destroy($transactionId)
    {
        // Logic to delete the transaction
    }
}
