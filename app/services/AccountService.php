<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Exception;

class AccountService
{
    /**
     * إضافة إيراد للحساب (زيادة الرصيد)
     */
    public function addIncome($accountId, $amount, $description = null)
    {
        return DB::transaction(function () use ($accountId, $amount, $description) {
            $account = Account::findOrFail($accountId);

            // زيادة الرصيد
            $account->current_balance += $amount;
            
            $account->save();

       

            return $account;
        });
    }

    /**
     * إضافة مصروف للحساب (خصم من الرصيد)
     */
    public function addExpense($accountId, $amount, $description = null)
    {
        return DB::transaction(function () use ($accountId, $amount, $description) {
            $account = Account::findOrFail($accountId);

            // التحقق أن الرصيد يكفي
            if ($account->current_balance < $amount) {
                throw new Exception("الرصيد غير كافي في الحساب لإجراء هذه العملية.");
            }

            // خصم الرصيد
            $account->current_balance -= $amount;
            $account->save();

          

            return $account;
        });
    }
}
