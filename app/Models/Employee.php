<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
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
    ];


    // علاقة الموظف مع المرتبات
    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    // علاقة الموظف مع الإجازات
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
    // علاقة الموظف مع الحضور
       public function users()
    {
        return $this->hasMany(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

}


