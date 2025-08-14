<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'check_in',
        'check_out',
        'hours',
        'attendance_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // حساب الساعات تلقائيًا عند وجود check_in و check_out
    public function calculateHours()
    {
        if ($this->check_in && $this->check_out) {
            $start = Carbon::parse($this->check_in);
            $end = Carbon::parse($this->check_out);
            return $start->diffInHours($end);
        }
        return 0;
    }
    
}
