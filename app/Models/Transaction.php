<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

protected $fillable = [
        'account_id', 'item_id', 'type', 'amount', 'transaction_date', 'notes',
    ];

    public function account()
{
    return $this->belongsTo(Account::class);
}

public function item()
{
    return $this->belongsTo(Item::class);
}

}
