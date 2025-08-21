<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class AccountService
{
    /**
     * تحديث رصيد الحساب بناءً على المعاملة
     */
    public function applyTransaction(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            // في حالة المصروفات => نقص من الحساب
            if ($transaction->type === 'مصروف') {
                $this->decrease($transaction->from_account_id, $transaction->amount);
            }

            // في حالة الإيرادات => زيادة للحساب
            if ($transaction->type === 'إيراد') {
                $this->increase($transaction->to_account_id, $transaction->amount);
            }

            // في حالة التحويل بين حسابين
            if ($transaction->type === 'تحويل') {
                $this->decrease($transaction->from_account_id, $transaction->amount);
                $this->increase($transaction->to_account_id, $transaction->amount);
            }
        });
    }

    /**
     * زيادة رصيد الحساب
     */
    public function increase($accountId, $amount): void
    {
        $account = Account::findOrFail($accountId);
        $account->balance += $amount;
        $account->save();
    }

    /**
     * إنقاص رصيد الحساب
     */
    public function decrease($accountId, $amount): void
    {
        $account = Account::findOrFail($accountId);

        // ممكن تضيف هنا حماية لو الرصيد غير كافي
        if ($account->balance < $amount) {
            throw new \Exception("الرصيد في الحساب {$account->name} غير كافٍ!");
        }

        $account->balance -= $amount;
        $account->save();
    }
}
