<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractPayment extends Model
{
    protected $fillable = [
        'contract_id', 'payment_type', 'title', 'stage', 'condition',
        'period_month', 'due_date', 'amount', 'include_tax', 'is_paid', 'notes',
    ];

    public const STAGES = [
        'contract' => 'تعاقد',
        'supply' => 'توريد',
        'training' => 'تدريب',
        'operation' => 'تشغيل',
        'migration' => 'تهجير البيانات',
        'soft_live' => 'سوفت لايف',
        'maintenance' => 'صيانة',
    ];

    protected $casts = [
        'period_month' => 'date',  // مهم عشان ما نكسر format()
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'include_tax' => 'boolean',
        'is_paid' => 'boolean',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
