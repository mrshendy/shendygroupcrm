<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'details',
        'status',
        'country_id',
        'client_id',
        'project_type',
        'programming_type',
        'phase',
        'start_date',
        'end_date',
        'priority',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // علاقة مع الدولة
    public function country()
    {
        return $this->belongsTo(countries::class);
    }

    // علاقة مع العميل
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    

}