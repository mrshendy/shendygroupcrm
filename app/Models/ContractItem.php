<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractItem extends Model
{
    protected $fillable = ['contract_id', 'title', 'body', 'sort_order'];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
