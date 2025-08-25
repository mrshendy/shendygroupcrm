<?php

namespace App\Http\Livewire\Offers;

use Livewire\Component;
use App\Models\Offer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $offers = [];
    public $totalOffers = 0;
    public $newOffers = 0;
    public $rejectedOffers = 0;
    public $closedOffers = 0;

    // هنعرف متغير يمسك الـ id اللي هيتحذف
    public $deleteId = null;

    protected $listeners = [
        // لو حبيت تستقبل أحداث من أماكن تانية
    ];

    public function mount()
    {
        // لو انت بتجيب الإحصائيات والـ offers في مكان تاني، سيبها زي ما هي.
        // ده مجرد مثال للتجهيز.
        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.offers.index'); // أو اسم الفيو اللي عندك
    }

    public function refreshData()
    {
        // استبدل ده بمنطقتك الحقيقية
        $this->offers = Offer::with(['client','project','latestFollowup.user'])->latest()->get();

        $this->totalOffers    = Offer::count();
        $this->newOffers      = Offer::where('status', Offer::STATUS_NEW)->count();
        $this->rejectedOffers = Offer::where('status', Offer::STATUS_REJECTED)->count();
        $this->closedOffers   = Offer::where('status', Offer::STATUS_CLOSED)->count();
    }

    // دي اللي بتتندَه من زرار "حذف" في الجدول
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        // نفتح المودال بالجافاسكربت بتاعك
        $this->dispatchBrowserEvent('open-delete-modal');
    }

    // دي اللي بتتندَه من زرار "نعم، احذف" داخل المودال
    public function delete()
    {
        try {
            if (!$this->deleteId) {
                // مفيش id
                $this->dispatchBrowserEvent('close-delete-modal');
                session()->flash('error', 'لا يوجد عنصر محدد للحذف.');
                return;
            }

            $offer = Offer::find($this->deleteId);

            if (!$offer) {
                $this->dispatchBrowserEvent('close-delete-modal');
                session()->flash('error', 'العرض غير موجود.');
                return;
            }

            // لو عندك صلاحيات أو تحقق إضافي اعمله هنا
            $offer->delete();

            // نقفل المودال ونفضي الـ id ونعمل رسالة نجاح
            $this->dispatchBrowserEvent('close-delete-modal');
            $this->deleteId = null;

            session()->flash('success', 'تم حذف العرض بنجاح.');

            // حدّث الجدول/العدادات
            $this->refreshData();

        } catch (\Throwable $e) {
            Log::error('Offer delete failed', [
                'offer_id' => $this->deleteId,
                'user_id'  => Auth::id(),
                'error'    => $e->getMessage(),
            ]);

            $this->dispatchBrowserEvent('close-delete-modal');
            session()->flash('error', 'حدث خطأ أثناء الحذف. حاول مرة أخرى.');
        }
    }
}
