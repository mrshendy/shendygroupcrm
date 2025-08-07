<?php

// Eloquent Model: app/Models/Account.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'id',
        'name',
        'type',
        'opening_balance',
        'current_balance',
        'account_number',
        'bank',
        'status',
        'notes',
        'is_main',
        'created_at',
        'updated_at',
    ];
}
