<?php

// Eloquent Model: app/Models/Account.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
    'name',
    'type',
    'opening_balance',
    'current_balance',
    'status',
    'notes'
];

}
