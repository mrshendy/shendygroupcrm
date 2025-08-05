<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'address',
        'country_id',
        'contact_name',
        'job',
        'contact_phone',
        'contact_email',
        'is_main_contact',
    ];

    // Client.php
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

     // العلاقة
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

}
