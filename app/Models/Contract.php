<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'client_id', 'project_id', 'offer_id',
        'start_date', 'end_date', 'type',
        'amount', 'include_tax', 'contract_file', 'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'include_tax' => 'boolean',
    ];

    public const TYPES = [
        'maintenance' => 'صيانة',
        'supply_install' => 'توريد وتركيب',
        'marketing' => 'تسويق',
        'software' => 'برمجة',
        'data_entry' => 'ادخال بيانات',
        'call_center' => 'كول سنتر',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function payments()
    {
        return $this->hasMany(ContractPayment::class);
    }

    public function items()
    {
        return $this->hasMany(ContractItem::class)->orderBy('sort_order');
    }
}
