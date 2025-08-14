<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'employee_id',
        'month',       // صيغة مثل 2025-08
        'basic_salary',
        'allowances',
        'deductions',
        'net_salary',
        'status',      // pending - paid
        'notes',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


}
