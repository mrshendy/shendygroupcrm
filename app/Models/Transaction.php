<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id', 'item_id', 'amount', 'transaction_type',
        'transaction_date', 'notes', 'collection_type', 'client_id',
    ];

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class);
    }

    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class);
    }

    public function item()
    {
        return $this->belongsTo(\App\Models\Item::class);
    }
}
