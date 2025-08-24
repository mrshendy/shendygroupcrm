<?php

namespace App\Http\Livewire\Offers;

use App\Models\Client;
use App\Models\Offer;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Traits\web\file_storage;

class Create extends Component
{
    use WithFileUploads;

    public $clients;
    public $projects = [];

    public $client_id;
    public $project_id;
    public $start_date;
    public $end_date;
    public $details;
    public $amount;
    public $include_tax = false;
    public $description;
    public $status;
    public $attachment;

    /** ✅ قواعد التحقق */
    protected $rules = [
        'client_id'   => 'required|exists:clients,id',
        'project_id'  => 'required|exists:projects,id',
        'start_date'  => 'required|date',
        'end_date'    => 'required|date|after_or_equal:start_date',
        'details'     => 'required|string|min:5|max:1000',
        'amount'      => 'required|numeric|min:1',
        'include_tax' => 'boolean',
        'description' => 'required|string|min:5|max:1000',
        'status'      => 'required|in:new,under_review,approved,contracting,rejected,pending,signed,closed',
        'attachment'  => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:4096',
    ];

    /** ✅ رسائل التحقق بالعربي */
    protected $messages = [
        'client_id.required'   => 'يجب اختيار العميل.',
        'client_id.exists'     => 'العميل غير موجود.',
        
        'project_id.required'  => 'يجب اختيار المشروع.',
        'project_id.exists'    => 'المشروع غير موجود.',

        'start_date.required'  => 'تاريخ بداية العرض مطلوب.',
        'start_date.date'      => 'صيغة تاريخ البداية غير صحيحة.',

        'end_date.required'    => 'تاريخ نهاية العرض مطلوب.',
        'end_date.date'        => 'صيغة تاريخ النهاية غير صحيحة.',
        'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية.',

        'details.required'     => 'تفاصيل العرض مطلوبة.',
        'details.min'          => 'تفاصيل العرض يجب أن تكون على الأقل 5 أحرف.',
        'details.max'          => 'تفاصيل العرض لا يجب أن تتجاوز 1000 حرف.',

        'amount.required'      => 'قيمة العرض مطلوبة.',
        'amount.numeric'       => 'قيمة العرض يجب أن تكون رقم.',
        'amount.min'           => 'قيمة العرض يجب أن تكون أكبر من الصفر.',

        'description.required' => 'الوصف مطلوب.',
        'description.min'      => 'الوصف يجب أن يحتوي على 5 أحرف على الأقل.',
        'description.max'      => 'الوصف لا يجب أن يتجاوز 1000 حرف.',

        'status.required'      => 'حالة العرض مطلوبة.',
        'status.in'            => 'حالة العرض غير صحيحة.',

        'attachment.file'      => 'الملف المرفق يجب أن يكون ملف صحيح.',
        'attachment.mimes'     => 'صيغة الملف يجب أن تكون PDF أو DOC أو صورة.',
        'attachment.max'       => 'حجم الملف يجب ألا يتجاوز 4 ميجا.',
    ];

    public function mount()
    {
        $this->clients = Client::orderBy('name')->get();
    }

    public function updatedClientId($value)
    {
        $this->projects = Client::find($value)?->projects()->orderBy('name')->get() ?? collect();
    }

    public function store()
    {
        $this->validate();

        // معالجة الملف
        $attachment = null;
        if ($this->attachment) {
            $result = $this->file_storage($this->attachment, 'offer_attachments');
            $attachment = is_array($result) ? ($result[0] ?? null) : $result;
        }

        Offer::create([
            'client_id'   => $this->client_id,
            'project_id'  => $this->project_id,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'details'     => $this->details,
            'amount'      => $this->amount,
            'include_tax' => $this->include_tax,
            'description' => $this->description,
            'status'      => $this->status,
            'attachment'  => $attachment,
        ]);

        session()->flash('success', '✅ تم إنشاء العرض بنجاح.');
        return redirect()->route('offers.index');
    }

    public function render()
    {
        return view('livewire.offers.create')->layout('layouts.master');
    }
}
