<?php

namespace App\Http\Livewire\Offers;

use Livewire\Component;
use App\Models\Offer;

class Index extends Component
{
    public $offers;
    public $totalOffers, $newOffers, $rejectedOffers, $closedOffers;
    public $offerToDelete = null;
    public $confirmingDelete = false; // ✅ للتحكم في إظهار المودال

    public function mount()
    {
        $this->loadStats();
        $this->offers = Offer::with(['client', 'project'])
            ->latest()
            ->get();
    }

    private function loadStats()
    {
        $this->totalOffers    = Offer::count(); // بدون المحذوفين Soft
        $this->newOffers      = Offer::where('status', 'new')->count();
        $this->rejectedOffers = Offer::where('status', 'rejected')->count();
        $this->closedOffers   = Offer::where('status', 'closed')->count();
    }

    /** فتح مودال التأكيد */
    public function confirmDelete($id)
    {
        $this->offerToDelete = $id;
        $this->confirmingDelete = true;
        $this->dispatchBrowserEvent('open-delete-modal');
    }

    /** تنفيذ الحذف (Soft Delete) */
    public function delete()
    {
        if ($this->offerToDelete) {
            $offer = Offer::find($this->offerToDelete);

            if ($offer) {
                $offer->delete(); // ✅ soft delete
            }

            $this->offerToDelete = null;
            $this->confirmingDelete = false;

            // تحديث البيانات
            $this->offers = Offer::with(['client', 'project'])->latest()->get();
            $this->loadStats();

            $this->dispatchBrowserEvent('close-delete-modal');
            session()->flash('success', '✅ تم حذف العرض بنجاح');
        }
    }

    public function render()
    {
        return view('livewire.offers.index', [
            'offers' => $this->offers,
        ]);
    }
}
