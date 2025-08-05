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

    protected $rules = [
        'status' => 'required',
        'notes' => 'nullable|string',
        'contract_date' => 'nullable|date',
        'waiting_date' => 'nullable|date',
        'contract_file' => 'nullable|file|mimes:pdf,doc,docx',
        'close_reason' => 'nullable|string',
        'reject_reason' => 'nullable|string',
        'confirm_close' => 'boolean',
    ];

    public function mount(Offer $offer)
    {
        $this->offer = $offer;
        $this->status = $offer->status;
    }

    public function updatedStatus()
    {
        $this->reset([
            'notes',
            'contract_date',
            'waiting_date',
            'contract_file',
            'close_reason',
            'reject_reason',
            'confirm_close'
        ]);
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->status === 'signed' && $this->contract_file) {
            $validatedData['contract_file'] = $this->contract_file->store('contracts', 'public');
        }

        if ($this->status === 'closed' && !$this->confirm_close) {
            session()->flash('error', 'يرجى تأكيد إغلاق العرض.');
            return; 
        }

        $this->offer->update([
            'status' => $this->status,
            'notes' => $validatedData['notes'] ?? null,
            'contract_date' => $validatedData['contract_date'] ?? null,
            'waiting_date' => $validatedData['waiting_date'] ?? null,
            'contract_file' => $validatedData['contract_file'] ?? null,
            'close_reason' => $validatedData['close_reason'] ?? null,
            'reject_reason' => $validatedData['reject_reason'] ?? null,
        ]);

        session()->flash('success', 'تم تحديث حالة العرض بنجاح.');

        return redirect()->route('offers.show', $this->offer->id);
    }

    public function render()
    {
        return view('livewire.offers.offer-status');
    }
}
