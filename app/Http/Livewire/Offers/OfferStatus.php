<?php

namespace App\Http\Livewire\Offers;

use Livewire\Component;
use App\Models\Offer;
use Livewire\WithFileUploads;

class OfferStatus extends Component
{
    use WithFileUploads;

    public Offer $offer;
    public $status;
    public $notes;
    public $contract_date;
    public $waiting_date;
    public $contract_file;
    public $close_reason;
    public $reject_reason;
    public $confirm_close = false;

    public function mount(Offer $offer)
    {
        $this->offer = $offer;
        $this->status = $offer->status;
        $this->notes = $offer->notes;
        $this->contract_date = $offer->contract_date;
        $this->waiting_date = $offer->waiting_date;
        $this->close_reason = $offer->close_reason;
        $this->reject_reason = $offer->reject_reason;
    }

    /** القواعد الديناميكية */
    public function rules()
    {
        $rules = [
            'status' => 'required',
            'notes' => 'nullable|string',
            'contract_date' => 'nullable|date',
            'waiting_date' => 'nullable|date',
            'contract_file' => 'nullable|file|mimes:pdf,doc,docx',
            'close_reason' => 'nullable|string',
            'reject_reason' => 'nullable|string',
            'confirm_close' => 'boolean',
        ];

        // الموافقة -> لازم تاريخ وملاحظات
        if ($this->status === 'approved') {
            $rules['contract_date'] = 'required|date';
            $rules['notes'] = 'required|string|min:3';
        }

        // الرفض -> سبب الرفض
        if ($this->status === 'rejected') {
            $rules['reject_reason'] = 'required|string|min:3';
        }

        // الانتظار -> لازم تاريخ الانتظار
        if ($this->status === 'pending') {
            $rules['waiting_date'] = 'required|date';
        }

        // التعاقد -> لازم ملف وملاحظات
        if ($this->status === 'signed') {
            $rules['contract_file'] = 'required|file|mimes:pdf,doc,docx';
            $rules['notes'] = 'required|string|min:3';
        }

        // إغلاق -> سبب الإغلاق + تأكيد الإغلاق
        if ($this->status === 'closed') {
            $rules['close_reason'] = 'required|string|min:3';
            $rules['confirm_close'] = 'accepted';
        }

        return $rules;
    }

    /** رسائل التحذير */
    protected $messages = [
        'status.required'        => 'يجب اختيار الحالة الجديدة.',
        'contract_date.required' => 'تاريخ إرسال العقد مطلوب.',
        'contract_date.date'     => 'أدخل تاريخ صالح لإرسال العقد.',
        'waiting_date.required'  => 'تاريخ الانتظار مطلوب.',
        'waiting_date.date'      => 'أدخل تاريخ صالح للانتظار.',
        'contract_file.required' => 'يجب رفع ملف العقد.',
        'contract_file.mimes'    => 'ملف العقد يجب أن يكون PDF أو Word.',
        'close_reason.required'  => 'سبب الإغلاق مطلوب.',
        'close_reason.string'    => 'سبب الإغلاق يجب أن يكون نصاً.',
        'reject_reason.required' => 'سبب الرفض مطلوب.',
        'reject_reason.string'   => 'سبب الرفض يجب أن يكون نصاً.',
        'notes.required'         => 'الملاحظات مطلوبة.',
        'notes.string'           => 'الملاحظات يجب أن تكون نصاً.',
        'notes.min'              => 'الملاحظات يجب ألا تقل عن 3 أحرف.',
        'confirm_close.accepted' => 'يجب تأكيد إغلاق العرض.',
    ];

    /** حفظ التعديلات */
    public function save()
    {
        $validatedData = $this->validate();

        // رفع ملف العقد لو الحالة = تم التعاقد
        if ($this->status === 'signed' && $this->contract_file) {
            $validatedData['contract_file'] = $this->contract_file->store('contracts', 'public');
        }

        $this->offer->update([
            'status'        => $this->status,
            'notes'         => $validatedData['notes'] ?? null,
            'contract_date' => $validatedData['contract_date'] ?? null,
            'waiting_date'  => $validatedData['waiting_date'] ?? null,
            'contract_file' => $validatedData['contract_file'] ?? null,
            'close_reason'  => $validatedData['close_reason'] ?? null,
            'reject_reason' => $validatedData['reject_reason'] ?? null,
        ]);

        session()->flash('success', '✅ تم تحديث حالة العرض بنجاح.');
        return redirect()->route('offers.show', $this->offer->id);
    }

    public function render()
    {
        return view('livewire.offers.offer-status');
    }
}
