<?php

// Eloquent Model: app/Models/Account.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'id',
        'name',
        'account_number',
        'type',
        'opening_balance',
        'bank',
        'is_main',
        'status',
        'notes',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function offers()
    {

        return $this->hasMany(Offer::class);
    }

    public function transactions()
    {

        return $this->hasMany(Transaction::class);
    }
}
