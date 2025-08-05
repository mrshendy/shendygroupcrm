<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    // توحيد الحالات كـ Constants
    const STATUS_NEW = 'new';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CLOSED = 'closed';
    const STATUS_SIGNED = 'signed';
    const STATUS_PENDING = 'pending';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_APPROVED = 'approved';
    const STATUS_CONTRACTING = 'contracting';

    protected $fillable = [
        'client_id',
        'project_id',
        'start_date',
        'end_date',
        'status',
        'details',
        'amount',
        'include_tax',
        'description',
        'attachment',
        'notes',
        'contract_date',
        'waiting_date',
        'contract_file',
        'close_reason',
        'reject_reason',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // إرجاع اسم الحالة بالعربية
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            self::STATUS_NEW => 'جديد',
            self::STATUS_REJECTED => 'تم رفض العرض',
            self::STATUS_CLOSED => 'عرض مغلق',
            self::STATUS_SIGNED => 'تم التعاقد',
            self::STATUS_PENDING => 'قيد الانتظار',
            self::STATUS_UNDER_REVIEW => 'تحت المتابعة',
            self::STATUS_APPROVED => 'تمت الموافقة على العرض',
            self::STATUS_CONTRACTING => 'جارٍ التعاقد',
            default => 'غير معروف',
        };
    }
}
