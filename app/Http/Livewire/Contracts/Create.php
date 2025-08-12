<?php

namespace App\Http\Livewire\Contracts;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Project;
use App\Models\Offer;
use App\Models\Contract;
use App\Models\ContractItem;
use App\Models\ContractPayment;

class Create extends Component
{
    use WithFileUploads;

    // وضع إضافة/تعديل
    public ?Contract $contract = null;

    // اختيارات القوائم
    public $client_id = null;
    public $project_id = null;
    public $offer_id   = null;

    // بيانات القوائم
    public $clients  = [];
    public $projects = [];
    public $offers   = [];

    // حقول العقد
    public $type;
    public $start_date;
    public $end_date;
    public $amount = 0;
    public $include_tax = false;
    public $status = 'active';

    // ملف العقد
    public $contract_file;

    // بنود ودفعات Livewire
    public $items = [];      // [['title'=>'', 'body'=>''], ...]
    public $payments = [];   // [['payment_type'=>'milestone', 'title'=>'', 'stage'=>'', 'condition'=>'date', 'due_date'=>null, 'amount'=>0, 'include_tax'=>false, 'is_paid'=>false, 'notes'=>null], ...]

    // القواعد
    protected $rules = [
        'client_id'   => 'required|exists:clients,id',
        'project_id'  => 'nullable|exists:projects,id',
        'offer_id'    => 'nullable|exists:offers,id',
        'type'        => 'required|string',
        'start_date'  => 'nullable|date',
        'end_date'    => 'nullable|date|after_or_equal:start_date',
        'amount'      => 'required|numeric|min:0',
        'include_tax' => 'boolean',
        'status'      => 'required|in:draft,active,suspended,completed,cancelled',
        'contract_file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:20480',

        // قواعد اختيارية للبنود/الدفعات
        'items.*.title' => 'nullable|string|max:255',
        'items.*.body'  => 'nullable|string',
        'payments.*.payment_type' => 'nullable|in:milestone,monthly',
        'payments.*.title'        => 'nullable|string|max:255',
        'payments.*.stage'        => 'nullable|string',
        'payments.*.condition'    => 'nullable|in:date,stage',
        'payments.*.due_date'     => 'nullable|date',
        'payments.*.amount'       => 'nullable|numeric|min:0',
        'payments.*.include_tax'  => 'nullable|boolean',
        'payments.*.is_paid'      => 'nullable|boolean',
        'payments.*.notes'        => 'nullable|string',
    ];

    protected $messages = [
        'client_id.required' => 'برجاء اختيار العميل.',
        'client_id.exists'   => 'العميل المختار غير موجود.',
        'project_id.exists'  => 'المشروع المختار غير موجود.',
        'offer_id.exists'    => 'العرض المختار غير موجود.',
        'type.required'      => 'نوع العقد مطلوب.',
        'start_date.date'    => 'تاريخ البداية غير صحيح.',
        'end_date.date'      => 'تاريخ النهاية غير صحيح.',
        'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية.',
        'amount.required'    => 'قيمة العقد مطلوبة.',
        'amount.numeric'     => 'قيمة العقد يجب أن تكون رقمًا.',
        'amount.min'         => 'قيمة العقد لا يجب أن تكون سالبة.',
        'status.in'          => 'حالة العقد غير صحيحة.',
        'contract_file.mimes' => 'صيغة الملف غير مدعومة.',
        'contract_file.max'   => 'حجم الملف كبير للغاية (الحد 20MB).',
    ];

    public function mount(?Contract $contract = null)
    {
        $this->contract = $contract;

        // تحميل العملاء
        $this->clients = Client::select('id', 'name')
            ->orderBy('name')
            ->get()
            ->toArray();

        if ($contract) {
            // ملء حقول العقد
            $this->client_id   = $contract->client_id;
            $this->project_id  = $contract->project_id;
            $this->offer_id    = $contract->offer_id;

            $this->type        = $contract->type;
            $this->start_date  = optional($contract->start_date)?->format('Y-m-d');
            $this->end_date    = optional($contract->end_date)?->format('Y-m-d');
            $this->amount      = $contract->amount;
            $this->include_tax = (bool) $contract->include_tax;
            $this->status      = $contract->status;

            // تحميل المشاريع والعروض
            $this->loadProjects();
            $this->loadOffers();

            // تحميل البنود والدفعات الحالية
            $this->items = $contract->items()
                ->select('title', 'body')
                ->orderBy('sort_order')
                ->get()
                ->toArray();

            $this->payments = $contract->payments()
                ->select('payment_type','title','stage','condition','due_date','amount','include_tax','is_paid','notes')
                ->orderBy('id')
                ->get()
                ->map(function ($p) {
                    return [
                        'payment_type' => $p->payment_type,
                        'title'        => $p->title,
                        'stage'        => $p->stage,
                        'condition'    => $p->condition,
                        'due_date'     => optional($p->due_date)?->format('Y-m-d'),
                        'amount'       => (float) $p->amount,
                        'include_tax'  => (bool) $p->include_tax,
                        'is_paid'      => (bool) $p->is_paid,
                        'notes'        => $p->notes,
                    ];
                })
                ->toArray();
        } else {
            // قيَم افتراضية
            $this->status = 'active';
            $this->amount = 0;

            // صف افتراضي
            $this->items = [
                ['title' => '', 'body' => '']
            ];
            $this->payments = []; // ابدأ بدون دفعات
        }
    }

    // تغييرات اختيار العميل/المشروع
    public function updatedClientId()
    {
        $this->project_id = null;
        $this->offer_id   = null;
        $this->projects   = [];
        $this->offers     = [];

        $this->loadProjects();
        $this->loadOffers();
    }

    public function updatedProjectId()
    {
        $this->offer_id = null;
        $this->loadOffers();
    }

    protected function loadProjects()
    {
        if (!$this->client_id) {
            $this->projects = [];
            return;
        }

        $this->projects = Project::query()
            ->select('id', 'name')
            ->where('client_id', $this->client_id)
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    protected function loadOffers()
    {
        $this->offers = Offer::query()
            ->select('id', 'start_date')
            ->when($this->client_id, fn ($q) => $q->where('client_id', $this->client_id))
            ->when($this->project_id, fn ($q) => $q->where('project_id', $this->project_id))
            ->orderByDesc('start_date')
            ->get()
            ->map(function ($o) {
                return [
                    'id'         => $o->id,
                    'start_date' => optional($o->start_date)?->format('Y-m-d'),
                ];
            })
            ->toArray();
    }

    // إدارة البنود
    public function addItem()
    {
        $this->items[] = ['title' => '', 'body' => ''];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    // إدارة الدفعات
    public function addPayment($type = 'milestone')
    {
        $this->payments[] = [
            'payment_type' => in_array($type, ['milestone','monthly']) ? $type : 'milestone',
            'title'        => '',
            'stage'        => '',
            'condition'    => 'date',
            'due_date'     => null,
            'amount'       => 0,
            'include_tax'  => false,
            'is_paid'      => false,
            'notes'        => '',
        ];
    }

    public function removePayment($index)
    {
        unset($this->payments[$index]);
        $this->payments = array_values($this->payments);
    }

   public function save()
{
    $data = $this->validate();

    if ($this->contract_file) {
        $data['contract_file'] = $this->contract_file->store('contracts');
    } elseif (!$this->contract) {
        $data['contract_file'] = null;
    }

    DB::beginTransaction();
    try {
        // إنشاء/تحديث العقد ثم refresh لضمان وجود الـ ID
        if ($this->contract) {
            $this->contract->update($data);
            $contract = $this->contract->refresh();

            // امسح القديم قبل البناء من جديد
            $contract->items()->delete();
            $contract->payments()->delete();
        } else {
            $contract = Contract::create($data);
            $contract->refresh();
            $this->contract = $contract;
        }

        // تحضير البنود (تجاهل الفارغ)
        $sort = 1;
        $preparedItems = [];
        foreach ($this->items as $it) {
            $title = isset($it['title']) ? trim((string)$it['title']) : '';
            $body  = $it['body'] ?? null;

            if ($title === '' && empty($body)) {
                continue;
            }

            $preparedItems[] = [
                'title'      => $title !== '' ? $title : 'بدون عنوان',
                'body'       => $body,
                'sort_order' => $sort++,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // تحضير الدفعات (تجاهل الصفوف الفارغة)
        $preparedPayments = [];
        foreach ($this->payments as $p) {
            $amountVal = isset($p['amount']) ? (float)$p['amount'] : 0;
            $ptype     = in_array(($p['payment_type'] ?? 'milestone'), ['milestone','monthly']) ? $p['payment_type'] : 'milestone';
            $cond      = in_array(($p['condition'] ?? 'date'), ['date','stage']) ? $p['condition'] : 'date';

            if ($amountVal <= 0 && empty($p['title'])) {
                continue;
            }

            $preparedPayments[] = [
                'payment_type' => $ptype,
                'title'        => $p['title'] ?? null,
                'stage'        => $p['stage'] ?? null,
                'period_month' => null, // غير مستخدم حالياً
                'due_date'     => !empty($p['due_date']) ? $p['due_date'] : null,
                'condition'    => $cond,
                'amount'       => $amountVal,
                'include_tax'  => !empty($p['include_tax']),
                'is_paid'      => !empty($p['is_paid']),
                'notes'        => $p['notes'] ?? null,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        // ✅ الحفظ عبر العلاقات يملأ contract_id تلقائياً
        if (!empty($preparedItems)) {
            $contract->items()->createMany($preparedItems);
        }
        if (!empty($preparedPayments)) {
            $contract->payments()->createMany($preparedPayments);
        }

        DB::commit();

        session()->flash('success', $this->contract->wasRecentlyCreated ? 'تم إنشاء العقد بنجاح.' : 'تم تحديث العقد بنجاح.');
        return redirect()->route('contracts.index');

    } catch (\Throwable $e) {
        DB::rollBack();
        report($e);
        session()->flash('error', 'حدث خطأ أثناء حفظ العقد. برجاء المحاولة مرة أخرى.');
        // ابقَ في نفس الصفحة لعرض الرسالة
    }
}

    public function render()
    {
        return view('livewire.contracts.create')
            ->extends('layouts.master')
            ->section('content');
    }
}
