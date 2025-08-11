<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractItem extends Model
{
    protected $fillable = [
        'contract_id',
        'title',
        'body',
        'sort_order',
    ];

    /**
     * العلاقة مع العقد
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * ترتيب البنود افتراضيًا حسب sort_order
     */
    protected static function booted()
    {
        static::addGlobalScope('order', function ($query) {
            $query->orderBy('sort_order', 'asc');
        });
    }
}
