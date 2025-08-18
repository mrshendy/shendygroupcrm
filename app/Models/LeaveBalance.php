<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    protected $table = 'leave_balances';

    protected $fillable = [
        'employee_id', 'year',
        'total_days', 'used_days', 'remaining_days',
        'annual_days', 'casual_days',
    ];

    protected $casts = [
        'year' => 'integer', 'total_days' => 'integer', 'used_days' => 'integer',
        'remaining_days' => 'integer', 'annual_days' => 'integer', 'casual_days' => 'integer',
    ];

    
    // كل إجازات الموظف في نفس السنة (هنستخدمها للتجميع)
    

    // مساعد لتحديث used/remaining من جدول leaves الموافق عليها
  
    public function employee()
{
    return $this->belongsTo(Employee::class, 'employee_id');
}

/** كل الإجازات لنفس الموظف ونفس السنة */
public function leavesOfYear()
{
    return $this->hasMany(Leave::class, 'employee_id', 'employee_id')
        ->whereColumn('leaves.year', 'leave_balances.year');
}

/** تجميع سريع */
public function approvedLeaves()
{
    return $this->leavesOfYear()->where('status','approved');
}

/** إعادة حساب الرصيد من جدول الإجازات */
public function recalcFromLeaves(): void
{
    $approved = (int)$this->approvedLeaves()->sum('days');
    $this->used_days      = $approved;
    $this->remaining_days = max(0, (int)$this->total_days - $approved);
    $this->save();
}

}
