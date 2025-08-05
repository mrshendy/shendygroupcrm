<?php
// app/Models/OfferFollowup.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferFollowup extends Model
{
    protected $fillable = ['offer_id', 'follow_up_date', 'type', 'description', 'user_id'];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
public function client()
{
    return $this->belongsTo(Client::class);
}
  
}
