<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'employee_id',
        'shift_id',
        'type',        // annual - sick - emergency
        'start_date',
        'end_date',
        'days_count',
        'reason',
        'status',      // pending - approved - rejected
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function balance()
    {
        return $this->hasOne(LeaveBalance::class, 'employee_id', 'employee_id')
            ->whereColumn('leave_balances.year', 'leaves.year');
    }

    /** سكوبات مفيدة */
    public function scopeApproved($q)
    {
        return $q->where('status', 'approved');
    }

    public function scopeForYear($q, int $year)
    {
        return $q->where('year', $year);
    }
}
