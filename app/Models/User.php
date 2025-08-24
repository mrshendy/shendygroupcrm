<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // كل مستخدم ممكن يكون عنده متابعات عروض
    public function offerFollowups()
    {
        return $this->hasMany(OfferFollowup::class);
    }

    // لو عايز تعرف العروض اللي أنشأها
    public function offers()
    {
        return $this->hasMany(Offer::class, 'created_by');
    }
}
