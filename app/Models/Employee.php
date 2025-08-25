<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
        use HasFactory, SoftDeletes;

    protected $table = 'employees';

    protected $fillable = [
        'full_name',
        'employee_code',
        'email',
        'phone',
        'job_title',
        'department',
        'employment_status',
        'employment_type',
        'salary',
        'hiring_date',
        'birth_date',
        'gender',
        'address',
        'notes',
        'avatar',
        'status',
        'position',
        'basic_salary',
        'shift_id', // ✅ مهم لو عندك العمود
    ];

    // (اختياري) كاست للتواريخ لو حابب
    protected $casts = [
        'hiring_date' => 'date',
        'birth_date' => 'date',
    ];

    // المرتبات
    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    // المستخدمين (لو فعلاً عندك أكثر من يوزر لنفس الموظف)
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // الحضور
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function shift()
    {
        return $this->belongsTo(\App\Models\Shift::class, 'shift_id');
    }

    public function leaves()
    {
        return $this->hasMany(\App\Models\Leave::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(\App\Models\LeaveBalance::class);
    }

    // رصيد سنة معيّنة
    public function leaveBalanceFor(int $year)
    {
        return $this->hasOne(LeaveBalance::class)->where('year', $year);
    }

    // سكوب يساعدك تجيب الموظف مع رصيد سنة محددة
    public function scopeWithBalanceYear($q, int $year)
    {
        return $q->with(['leaveBalances' => fn ($b) => $b->where('year', $year)]);
    }
}
