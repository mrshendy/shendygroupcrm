<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'name', 'days', 'start_time', 'end_time', 'leave_allowance',
    ];

    protected $casts = [
        'days' => 'array', // يخزن الأيام كـ JSON
    ];

   
      public function employees()
    {
        return $this->hasMany(Employee::class, 'shift_id');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}
