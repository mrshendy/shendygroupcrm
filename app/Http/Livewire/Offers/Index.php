<?php

namespace App\Http\Livewire\Offers;

use Livewire\Component;
use App\Models\Offer;

class Index extends Component
{
    public $offers;
    public $totalOffers, $newOffers, $rejectedOffers, $closedOffers;
    public $offerToDelete = null;

    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function mount()
    {
        $this->loadStats();
        $this->offers = Offer::with(['client', 'project'])->latest()->get();
    }

    public function loadStats()
    {
        $this->totalOffers    = Offer::count();
        $this->newOffers      = Offer::where('status', 'new')->count();
        $this->rejectedOffers = Offer::where('status', 'rejected')->count();
        $this->closedOffers   = Offer::where('status', 'closed')->count();
    }

    /** يفتح نافذة التأكيد */
    public function confirmDelete($id)
    {
        $this->offerToDelete = $id;
        $this->dispatchBrowserEvent('show-delete-confirmation');
    }

    /** ينفذ الحذف */
    public function delete()
    {
        if ($this->offerToDelete) {
            $offer = Offer::find($this->offerToDelete);
            if ($offer) {
                $offer->delete();
            }
            $this->offerToDelete = null;

            // إعادة تحميل البيانات
            $this->offers = Offer::with(['client', 'project'])->latest()->get();
            $this->loadStats();

            $this->dispatchBrowserEvent('offer-deleted');
        }
    }

    public function render()
    {
        return view('livewire.offers.index', [
            'offers' => $this->offers,
        ]);
    }
}
