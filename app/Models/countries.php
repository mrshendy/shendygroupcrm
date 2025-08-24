<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;

class countries extends Model
{
    use HasTranslations, SoftDeletes;

    protected $fillable = [
        'name',
        'notes',
        'user_add',
    ];

    public $translatable = ['name'];
    protected $table = 'countries';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
}
