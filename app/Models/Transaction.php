<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'amount',
        'transaction_date',
        'transaction_type',
        'from_account_id',
        'to_account_id',
        'item_id',
        'collection_type',
        'client_id',
        'notes',
        'user_add', // المستخدم الذي أضاف الحركة
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

    public function fromAccount()
    {
        return $this->belongsTo(\App\Models\Account::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(\App\Models\Account::class, 'to_account_id');
    }


}
