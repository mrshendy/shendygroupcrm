<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractItem extends Model
{
    // الحقول القابلة للإدخال
    protected $fillable = ['contract_id', 'title', 'body', 'sort_order'];

    // العلاقة مع العقد
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
