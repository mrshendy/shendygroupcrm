<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'employee_id',
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
}
