<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'client_id',
        'name',
        'description',
        'project_type',
        'programming_type',
        'phase',
        'details',
        'start_date',
        'end_date',
        'priority',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function country()
    {
        return $this->belongsTo(\App\Models\countries::class, 'country_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}
