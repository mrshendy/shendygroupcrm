<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
       'name', 'email', 'phone', 'status',
    'address', 'country', 'country_id',
    'job', // وظيفة العميل
    'contact_name', 'contact_job', // ✅ وظيفة المسؤول
    'contact_phone', 'contact_email',
    'is_primary', 'is_main_contact',
    ];

    public function country()
    {
        return $this->belongsTo(\App\Models\countries::class, 'country_id');
    }

    public function countryRelation()
    {
        return $this->belongsTo(\App\Models\countries::class, 'country_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
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
