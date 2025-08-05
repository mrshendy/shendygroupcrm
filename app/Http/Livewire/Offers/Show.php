<?php
namespace App\Http\Livewire\Offers;

use Livewire\Component;
use App\Models\Offer;

class Show extends Component
{
    public $offer;
    public $offerId;

    public function mount($id)
    {
        $this->offerId = $id;
        $this->offer = Offer::with(['client', 'project'])->findOrFail($id);
    }

    public function delete($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->delete();

        // إعادة تحميل العرض (إن وجد)
        $this->offer = null;

        // أو يمكنك عمل redirect
        return redirect()->route('offers.index')->with('success', 'تم حذف العرض بنجاح');
    }

    public function render()
    {
        return view('livewire.offers.show');
    }
}
