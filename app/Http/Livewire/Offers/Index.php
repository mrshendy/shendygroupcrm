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
    public $pendingOffers;     // قيد الانتظار
    public $signedOffers;      // تم التعاقد

    public function mount()
    {
        $this->loadStats();

        // تحميل العروض + العميل + المشروع + آخر متابعة + المستخدم الخاص بالمتابعة
        $this->offers = Offer::with(['client', 'project', 'latestFollowup.user'])
            ->latest()
            ->get();
    }

    public function loadStats()
    {
        $this->totalOffers    = Offer::count();
        $this->newOffers      = Offer::where('status', Offer::STATUS_NEW)->count();
        $this->rejectedOffers = Offer::where('status', Offer::STATUS_REJECTED)->count();
        $this->closedOffers   = Offer::where('status', Offer::STATUS_CLOSED)->count();
        $this->pendingOffers  = Offer::where('status', Offer::STATUS_PENDING)->count();
        $this->signedOffers   = Offer::where('status', Offer::STATUS_SIGNED)->count();
    }

    public function delete($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->delete();

        // إعادة تحميل البيانات
        $this->offers = Offer::with(['client', 'project', 'latestFollowup.user'])
            ->latest()
            ->get();

        $this->loadStats();

        // إرسال إشعار للواجهة
        $this->dispatchBrowserEvent('offerDeleted');
    }

    public function render()
    {
        return view('livewire.offers.index', [
            'offers' => $this->offers,
        ]);
    }
}
