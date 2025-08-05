<?php

namespace App\Http\Livewire\Offers;

use Livewire\Component;
use App\Models\Offer;
use App\Models\OfferFollowup;
use Illuminate\Support\Facades\Auth;

class Followup extends Component
{
    public $offer;
    public $followups = [];

    public $follow_up_date;
    public $type;
    public $description;

    public function mount($offerId)
    {
        $this->offer = Offer::findOrFail($offerId);
        $this->loadFollowups();
    }

    public function loadFollowups()
    {
        $this->followups = OfferFollowup::where('offer_id', $this->offer->id)
            ->with('client')
            ->orderBy('follow_up_date', 'desc')
            ->get();
    }

    public function saveFollowup()
    {
        $this->validate([
            'follow_up_date' => 'required|date',
            'type' => 'required|string',
            'description' => 'nullable|string',
        ]);

        OfferFollowup::create([
            'offer_id' => $this->offer->id,
            'follow_up_date' => $this->follow_up_date,
            'type' => $this->type,
            'description' => $this->description,
            'user_id' => Auth::id(),
        ]);

        $this->reset(['follow_up_date', 'type', 'description']);
        $this->loadFollowups();

        session()->flash('success', 'تمت إضافة المتابعة بنجاح.');
    }

    public function render()
    {
        return view('livewire.offers.followUp');
    }
}
