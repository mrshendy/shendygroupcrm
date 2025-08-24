<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferFollowup extends Model
{
    protected $table = 'offer_followups';

    protected $fillable = [
        'offer_id',
        'follow_up_date',
        'type',
        'description',
        'user_id'
    ];

    // العرض الأساسي
    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }

    // المستخدم اللي عمل المتابعة
    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}


    // ✅ العميل يوصل له عن طريق العرض
    public function client()
    {
        return $this->hasOneThrough(
            Client::class,
            Offer::class,
            'id',        // المفتاح في offers
            'id',        // المفتاح في clients
            'offer_id',  // المفتاح في offer_followups
            'client_id'  // المفتاح في offers
        );
    }
}
