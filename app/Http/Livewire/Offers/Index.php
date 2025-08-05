<?php
namespace App\Http\Livewire\Offers;

use Livewire\Component;
use App\Models\Offer;

class Index extends Component
{
    public $offers;
    public $totalOffers;
    public $newOffers;
    public $rejectedOffers;
    public $closedOffers;

    public function mount()
    {
        $this->loadStats();
        $this->offers = Offer::with(['client', 'project'])->get();
    }

    public function loadStats()
    {
        $this->totalOffers = Offer::count();
        $this->newOffers = Offer::where('status', 'new')->count();
        $this->rejectedOffers = Offer::where('status', 'rejected')->count();
        $this->closedOffers = Offer::where('status', 'closed')->count();
    }

    public function delete($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->delete();
        $this->mount();
        $this->dispatchBrowserEvent('offerDeleted');
    }

    public function render()
    {
        return view('livewire.offers.index');
    }
}
