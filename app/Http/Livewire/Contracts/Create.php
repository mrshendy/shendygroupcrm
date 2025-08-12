<?php

namespace App\Http\Livewire\contracts;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Models\Client;
use App\Models\Project;
use App\Models\Offer;
use App\Models\Contract;
use App\Models\ContractItem;
use App\Models\ContractPayment;

class Create extends Component
{
    use WithFileUploads;

    /** =======================
     *  متغير يحدد وضع الكلاس (إنشاء/تعديل)
     *  لو فيه قيمة => تعديل، لو null => إنشاء جديد
     *  ======================= */
    public ?int $contractId = null;

    /** =======================
     *  كائن العقد عند التعديل (للاستخدام داخل الفيو)
     *  ======================= */
    public $contract;

    /** =======================
     *  حقول بيانات العقد (مطابقة للفيو 1:1)
     *  ======================= */
    public $client_id;
    public $project_id;
    public $offer_id;

    public $type = 'software';
    public $start_date;
    public $end_date;
    public $amount;
    public $include_tax = false;
    public $contract_file; // Livewire Temp UploadedFile
    public $status = 'draft';

    /** =======================
     *  Data Sources للقوائم المعتمدة
     *  ======================= */
    public $clients = [];
    public $projects = [];
    public $offers = [];

    /** =======================
     *  المصفوفات الديناميكية الخاصة بالبُنود والدفعات
     *  ======================= */
    public $items = [];     // كل عنصر: ['title' => '', 'body' => '', 'sort_order' => int]
    public $payments = [];  // كل عنصر: ['payment_type','title','stage','condition','due_date','amount','include_tax','is_paid','notes']

    /**
     * قواعد التحقق العامة للفورم
     * - لاحظ استخدام Rule::in لقيم type/status
     * - التحقق على عناصر المصفوفات items / payments
     */
    protected function rules()
    {
        return [
            'client_id'   => ['required', 'integer', 'exists:clients,id'],
            'project_id'  => ['nullable', 'integer', 'exists:projects,id'],
            'offer_id'    => ['nullable', 'integer', 'exists:offers,id'],

            'type'        => ['required', Rule::in(array_keys(Contract::TYPES))],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'amount'      => ['required', 'numeric', 'min:0'],
            'include_tax' => ['boolean'],
            'contract_file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,png,jpg,jpeg'],
            'status'      => ['required', Rule::in(['draft','active','suspended','completed','cancelled'])],

            // بنود العقد
            'items'                  => ['array'],
            'items.*.title'          => ['nullable', 'string', 'max:255'],
            'items.*.body'           => ['nullable', 'string'],
            'items.*.sort_order'     => ['nullable', 'integer', 'min:0'],

            // دفعات العقد
            'payments'                      => ['array'],
            'payments.*.payment_type'       => ['required', Rule::in(['milestone','monthly'])],
            'payments.*.title'              => ['nullable', 'string', 'max:255'],
            'payments.*.stage'              => ['nullable', Rule::in(array_keys(ContractPayment::STAGES))],
            'payments.*.condition'          => ['nullable', Rule::in(['date','stage'])],
            'payments.*.due_date'           => ['nullable', 'date'],
            'payments.*.amount'             => ['nullable', 'numeric', 'min:0'],
            'payments.*.include_tax'        => ['boolean'],
            'payments.*.is_paid'            => ['boolean'],
            'payments.*.notes'              => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * رسائل مخصصة مختصرة بالعربي (حافظ على بساطة الرسالة)
     */
    protected $messages = [
        'client_id.required' => 'اختَر العميل.',
        'type.required'      => 'اختَر نوع العقد.',
        'amount.required'    => 'أدخل إجمالي العقد.',
        'amount.numeric'     => 'قيمة الإجمالي يجب أن تكون رقم.',
        'end_date.after_or_equal' => 'تاريخ نهاية العقد لا يجب أن يكون قبل البداية.',
        'contract_file.mimes' => 'صيغة الملف يجب أن تكون PDF/Word/صورة.',
        'contract_file.max'   => 'حجم الملف بحد أقصى 10 ميجا.',
        'payments.*.payment_type.in' => 'نوع الدفعة غير صحيح.',
    ];

    /**
     * mount:
     * - تحميل القوائم الأساسية (العملاء)
     * - تحديد وضع الكومبوننت (إنشاء/تعديل)
     * - تحميل العقد والبنود والدفعات عند التعديل
     */
    public function mount(?int $contractId = null): void
    {
        $this->contractId = $contractId;

        // تحميل العملاء (خفيف: id & name فقط)
        $this->clients = Client::query()->select('id','name')->orderBy('name')->get()->toArray();

        if ($this->contractId) {
            // تعديل: جلب العقد وعلاقاته
            $this->contract = Contract::with(['items','payments'])->findOrFail($this->contractId);

            // تعبئة الحقول من العقد الحالي
            $this->client_id   = $this->contract->client_id;
            $this->project_id  = $this->contract->project_id;
            $this->offer_id    = $this->contract->offer_id;
            $this->type        = $this->contract->type;
            $this->start_date  = optional($this->contract->start_date)->format('Y-m-d');
            $this->end_date    = optional($this->contract->end_date)->format('Y-m-d');
            $this->amount      = $this->contract->amount;
            $this->include_tax = (bool) $this->contract->include_tax;
            $this->status      = $this->contract->status;

            // تحميل المشاريع/العروض المعتمدة على العميل/المشروع
            $this->loadProjects();
            $this->loadOffers();

            // تحويل البنود لمصفوفة مناسبة للفيو
            $this->items = $this->contract->items
                ->sortBy('sort_order')
                ->map(function ($it) {
                    return [
                        'title'      => $it->title,
                        'body'       => $it->body,
                        'sort_order' => (int) $it->sort_order,
                    ];
                })->values()->toArray();

            // تحويل الدفعات لمصفوفة مناسبة للفيو
            $this->payments = $this->contract->payments
                ->sortBy(['due_date','id'])
                ->map(function ($p) {
                    return [
                        'payment_type' => $p->payment_type, // milestone/monthly
                        'title'        => $p->title,
                        'stage'        => $p->stage,
                        'condition'    => $p->condition,    // date/stage
                        'due_date'     => optional($p->due_date)->format('Y-m-d'),
                        'amount'       => $p->amount,
                        'include_tax'  => (bool) $p->include_tax,
                        'is_paid'      => (bool) $p->is_paid,
                        'notes'        => $p->notes,
                    ];
                })->values()->toArray();
        } else {
            // إنشاء: قيم ابتدائية نظيفة
            $this->contract = null;
            $this->items = [];
            $this->payments = [];
        }
    }

    /**
     * render:
     * - يمرر نفس متغير $contract للفيو عشان العنوان يتغير (تعديل/إنشاء)
     * - خليك متأكد من مسار الفيو
     */
    public function render()
    {
        return view('livewire.contracts.Create', [
            'contract' => $this->contract,
        ]);
    }

    /**
     * لما المستخدم يغيّر العميل:
     * - نحمل المشاريع المرتبطة ونفضي العرض
     * - نعيد ضبط project_id/offer_id
     */
    public function updatedClientId(): void
    {
        $this->project_id = null;
        $this->offer_id = null;
        $this->offers = [];
        $this->loadProjects();
    }

    /**
     * لما المستخدم يغيّر المشروع:
     * - نحمل العروض المرتبطة ونضبط offer_id
     */
    public function updatedProjectId(): void
    {
        $this->offer_id = null;
        $this->loadOffers();
    }

    /**
     * تحميل المشاريع حسب client_id
     */
    protected function loadProjects(): void
    {
        if (!$this->client_id) {
            $this->projects = [];
            return;
        }

        $this->projects = Project::query()
            ->select('id','name')
            ->where('client_id', $this->client_id)
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    /**
     * تحميل العروض حسب project_id
     */
    protected function loadOffers(): void
    {
        if (!$this->project_id) {
            $this->offers = [];
            return;
        }

        $this->offers = Offer::query()
            ->select('id','start_date')
            ->where('project_id', $this->project_id)
            ->orderByDesc('id')
            ->get()
            ->map(function ($of) {
                return [
                    'id' => $of->id,
                    'start_date' => optional($of->start_date)->format('Y-m-d'),
                ];
            })->toArray();
    }

    /**
     * إضافة بند جديد للمصفوفة items
     */
    public function addItem(): void
    {
        $this->items[] = [
            'title'      => '',
            'body'       => '',
            'sort_order' => count($this->items), // الفرز حسب ترتيب الإضافة
        ];
    }

    /**
     * حذف بند بناءً على الفهرس
     */
    public function removeItem(int $index): void
    {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
            $this->items = array_values($this->items); // إعادة ترقيم الفهارس
            // تحديث sort_order بما يطابق الفهرس
            foreach ($this->items as $i => &$it) {
                $it['sort_order'] = $i;
            }
        }
    }

    /**
     * إضافة دفعة (مرحلية/شهرية)
     * $type = 'milestone' | 'monthly'
     */
    public function addPayment(string $type = 'milestone'): void
    {
        $this->payments[] = [
            'payment_type' => in_array($type, ['milestone','monthly']) ? $type : 'milestone',
            'title'        => '',
            'stage'        => null,          // يستخدم فقط لو النوع مرحلي
            'condition'    => 'date',        // date أو stage
            'due_date'     => null,
            'amount'       => null,
            'include_tax'  => false,
            'is_paid'      => false,
            'notes'        => null,
        ];
    }

    /**
     * حذف دفعة حسب الفهرس
     */
    public function removePayment(int $index): void
    {
        if (isset($this->payments[$index])) {
            unset($this->payments[$index]);
            $this->payments = array_values($this->payments);
        }
    }

    /**
     * حفظ العقد (إنشاء/تحديث) مع البنود والدفعات
     * - استخدام معاملات DB::transaction لضمان التناسق
     * - رفع الملف (إن وجد) إلى storage/ (المسار: contracts/)
     */
    public function save()
    {
        // 1) تحقق أولي
        $validated = $this->validate();

        // 2) بعض التنظيف المنطقي قبل الحفظ:
        //    - لو نوع الدفعة "شهرية" نخلي stage = null
        //    - لو الشرط "date" ومفيش due_date، نسيبها null (مسموح)
        $paymentsClean = collect($this->payments)->map(function ($p) {
            $p['payment_type'] = $p['payment_type'] ?? 'milestone';
            if (($p['payment_type'] ?? '') === 'monthly') {
                $p['stage'] = null;
            }
            if (($p['condition'] ?? 'date') === 'stage' && empty($p['stage'])) {
                // لو الشرط مرحلة لازم يبقى فيه stage
                // هنسيبه null للتحقق؛ القاعدة تسمح null، لكن الأفضل نعالجه منطقيًا حسب استخدامك
            }
            // تطبيع القيم البوليانية
            $p['include_tax'] = (bool) ($p['include_tax'] ?? false);
            $p['is_paid']     = (bool) ($p['is_paid'] ?? false);
            return $p;
        })->toArray();

        // 3) رفع الملف (إن وُجد) قبل المعاملة، مع الاحتفاظ بالمسار لاستخدامه
        $storedPath = null;
        if ($this->contract_file) {
            // التخزين على disk الافتراضي داخل مجلد contracts
            $storedPath = $this->contract_file->store('contracts');
        }

        // 4) تنفيذ المعاملة
        DB::beginTransaction();

        try {
            // 4.1) إنشاء/تحديث العقد
            if ($this->contractId) {
                // تحديث
                $contract = Contract::lockForUpdate()->findOrFail($this->contractId);
                $contract->client_id   = $this->client_id;
                $contract->project_id  = $this->project_id;
                $contract->offer_id    = $this->offer_id;
                $contract->type        = $this->type;
                $contract->start_date  = $this->start_date ?: null;
                $contract->end_date    = $this->end_date ?: null;
                $contract->amount      = $this->amount;
                $contract->include_tax = (bool) $this->include_tax;
                $contract->status      = $this->status;

                if ($storedPath) {
                    // لو تم رفع ملف جديد، استبدل المسار
                    $contract->contract_file = $storedPath;
                }

                $contract->save();
            } else {
                // إنشاء
                $contract = Contract::create([
                    'client_id'   => $this->client_id,
                    'project_id'  => $this->project_id,
                    'offer_id'    => $this->offer_id,
                    'type'        => $this->type,
                    'start_date'  => $this->start_date ?: null,
                    'end_date'    => $this->end_date ?: null,
                    'amount'      => $this->amount,
                    'include_tax' => (bool) $this->include_tax,
                    'contract_file' => $storedPath,
                    'status'      => $this->status,
                ]);

                // ضبط وضع التعديل بعد الإنشاء
                $this->contractId = $contract->id;
                $this->contract   = $contract;
            }

            // 4.2) حفظ البنود:
            //      - أبسط طريقة: حذف القديم وإعادة الإنشاء (لو عايز نحسّن لاحقاً نعمل sync أذكى)
            $contract->items()->delete();

            // إعادة ترتيب sort_order حسب الفهرس الحالي
            foreach ($this->items as $i => $it) {
                ContractItem::create([
                    'contract_id' => $contract->id,
                    'title'       => $it['title'] ?? null,
                    'body'        => $it['body']  ?? null,
                    'sort_order'  => $i,
                ]);
            }

            // 4.3) حفظ الدفعات: حذف القديم وإعادة الإنشاء
            $contract->payments()->delete();

            foreach ($paymentsClean as $p) {
                ContractPayment::create([
                    'contract_id'  => $contract->id,
                    'payment_type' => $p['payment_type'] ?? 'milestone',
                    'title'        => $p['title'] ?? null,
                    'stage'        => $p['stage'] ?? null,
                    'period_month' => null, // غير مستخدمة في الفيو الحالي؛ تركناها null
                    'condition'    => $p['condition'] ?? 'date',
                    'due_date'     => $p['due_date'] ?? null,
                    'amount'       => $p['amount'] ?? 0,
                    'include_tax'  => (bool) ($p['include_tax'] ?? false),
                    'is_paid'      => (bool) ($p['is_paid'] ?? false),
                    'notes'        => $p['notes'] ?? null,
                ]);
            }

            DB::commit();

            // 5) رسالة نجاح واجهة
            session()->flash('success', $this->contractId ? 'تم تحديث العقد بنجاح' : 'تم إنشاء العقد بنجاح');
        } catch (\Throwable $e) {
            DB::rollBack();

            // لو كنا رفعنا ملف جديد وفشلت العملية، احذف الملف لتفادي orphan files
            if ($storedPath) {
                try { Storage::delete($storedPath); } catch (\Throwable $t) {}
            }

            // رسالة خطأ واجهة + لوج داخلي لو تحب
            session()->flash('error', 'حدث خطأ أثناء حفظ العقد. برجاء المحاولة مرة أخرى.');
            report($e);
        }
    }
}
