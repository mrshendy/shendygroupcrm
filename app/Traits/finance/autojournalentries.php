<?php


namespace App\Traits\finance;
use App\Models\finance\glentry;

trait autojournalentries
{
    /**
     * Boot the trait: ربط الأحداث بنموذج الـ Eloquent
     */
    public static function bootAutoJournalEntries()
    {
        // عند إنشاء السجل
        static::created(function ($model) {
            $model->generateJournalEntries('created');
        });

        // عند تحديث السجل
        static::updated(function ($model) {
            $model->generateJournalEntries('updated');
        });

        // عند حذف السجل
        static::deleted(function ($model) {
            $model->generateJournalEntries('deleted');
        });
    }

    /**
     * توليد القيود بناءً على نوع العملية
     *
     * @param string $action  // 'created' | 'updated' | 'deleted'
     * @return void
     */
    protected function generateJournalEntries(string $action): void
    {
        // مثالٍ توضيحي: 
        // على حسب الـ $this->debit_account_id و $this->credit_account_id في الموديل
        if (!isset($this->debit_account_id, $this->credit_account_id, $this->amount)) {
            return;
        }

        // وصف القيد
        $description = __("journal_trans.{$action}_description", [
            'model' => class_basename($this),
            'id'    => $this->getKey(),
        ]);

        // إنشاء قيد مدين
        glentry::create([
            'date'              => now()->toDateString(),
            'debit_account_id'  => $this->debit_account_id,
            'credit_account_id' => $this->credit_account_id,
            'amount'            => $this->amount,
            'reference_type'    => get_class($this),
            'reference_id'      => $this->getKey(),
            'description'       => $description,
        ]);
    }
}