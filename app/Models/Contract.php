<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
        use HasFactory, SoftDeletes;

    // الحقول القابلة للإدخال الجماعي
    protected $fillable = [
        'client_id', 'project_id', 'offer_id',
        'start_date', 'end_date',
        'type', 'amount', 'include_tax', 'contract_file', 'status',
    ];

    // أنواع العقود المتاحة
    public const TYPES = [
        'maintenance'     => 'صيانة',
        'supply_install'  => 'توريد وتركيب',
        'marketing'       => 'تسويق',
        'software'        => 'برمجة',
        'data_entry'      => 'ادخال بيانات',
        'call_center'     => 'كول سنتر',
    ];

    // التحويل التلقائي للأنواع
    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'amount'      => 'decimal:2',
        'include_tax' => 'boolean',
    ];

    // العلاقة مع العميل
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // العلاقة مع المشروع
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // العلاقة مع العرض
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    // العلاقة مع البنود
    public function items()
    {
        return $this->hasMany(ContractItem::class)->orderBy('sort_order');
    }

    // العلاقة مع الدفعات
    public function payments()
    {
        return $this->hasMany(ContractPayment::class)
                    ->orderBy('due_date')
                    ->orderBy('id');
    }
}
