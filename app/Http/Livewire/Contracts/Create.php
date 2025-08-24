<?php

namespace App\Http\Livewire\Contracts;

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

    public ?int $contractId = null;
    public $contract;

    // الحقول الأساسية
    public $client_id;
    public $project_id;
    public $offer_id;
    public $type = 'software';
    public $start_date;
    public $end_date;
    public $amount;
    public $include_tax = false;
    public $contract_file;
    public $status = 'draft';

    // البيانات المساعدة
    public $clients = [];
    public $projects = [];
    public $offers = [];

    // المصفوفات الديناميكية
    public $items = [];
    public $payments = [];

    /** قواعد التحقق */
    protected function rules()
    {
        return [
            'client_id'   => ['required', 'integer', 'exists:clients,id'],
            'project_id'  => ['nullable', 'integer', 'exists:projects,id'],
            'offer_id'    => ['nullable', 'integer', 'exists:offers,id'],

            'type'        => ['required', Rule::in(array_keys(Contract::TYPES))],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after_or_equal:start_date'],
            'amount'      => ['required', 'numeric', 'min:1'],
            'include_tax' => ['boolean'],
            'contract_file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,png,jpg,jpeg'],
            'status'      => ['required', Rule::in(['draft','active','suspended','completed','cancelled'])],

            // بنود العقد
            'items'                  => ['array'],
            'items.*.title'          => ['required_with:items.*.body', 'string', 'max:255'],
            'items.*.body'           => ['nullable', 'string'],
            'items.*.sort_order'     => ['nullable', 'integer', 'min:0'],

            // دفعات العقد
            'payments'                      => ['array'],
            'payments.*.payment_type'       => ['required', Rule::in(['milestone','monthly'])],
            'payments.*.title'              => ['nullable', 'string', 'max:255'],
            'payments.*.stage'              => ['nullable', Rule::in(array_keys(ContractPayment::STAGES))],
            'payments.*.condition'          => ['required', Rule::in(['date','stage'])],
            'payments.*.due_date'           => ['nullable', 'date', 'after_or_equal:start_date'],
            'payments.*.amount'             => ['required', 'numeric', 'min:1'],
            'payments.*.include_tax'        => ['boolean'],
            'payments.*.is_paid'            => ['boolean'],
            'payments.*.notes'              => ['nullable', 'string', 'max:1000'],
        ];
    }

    /** رسائل التحقق بالعربي */
    protected $messages = [
        'client_id.required' => 'اختَر العميل.',
        'type.required'      => 'اختَر نوع العقد.',
        'amount.required'    => 'أدخل إجمالي العقد.',
        'amount.min'         => 'قيمة العقد يجب أن تكون أكبر من صفر.',
        'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية.',
        'contract_file.mimes' => 'الملف يجب أن يكون بصيغة PDF أو Word أو صورة.',
        'contract_file.max'   => 'الملف لا يجب أن يتجاوز 10 ميجا.',

        // البنود
        'items.*.title.required_with' => 'عنوان البند مطلوب إذا كان فيه تفاصيل.',

        // الدفعات
        'payments.*.payment_type.required' => 'حدد نوع الدفعة.',
        'payments.*.amount.required'       => 'أدخل قيمة الدفعة.',
        'payments.*.amount.min'            => 'قيمة الدفعة يجب أن تكون أكبر من صفر.',
        'payments.*.due_date.after_or_equal' => 'تاريخ استحقاق الدفعة لا يمكن أن يكون قبل بداية العقد.',
    ];

    /** mount */
    public function mount(?int $contractId = null): void
    {
        $this->contractId = $contractId;
        $this->clients = Client::select('id','name')->orderBy('name')->get()->toArray();

        if ($this->contractId) {
            $this->contract = Contract::with(['items','payments'])->findOrFail($this->contractId);

            $this->client_id   = $this->contract->client_id;
            $this->project_id  = $this->contract->project_id;
            $this->offer_id    = $this->contract->offer_id;
            $this->type        = $this->contract->type;
            $this->start_date  = optional($this->contract->start_date)->format('Y-m-d');
            $this->end_date    = optional($this->contract->end_date)->format('Y-m-d');
            $this->amount      = $this->contract->amount;
            $this->include_tax = (bool) $this->contract->include_tax;
            $this->status      = $this->contract->status;

            $this->loadProjects();
            $this->loadOffers();

            $this->items = $this->contract->items->sortBy('sort_order')->map(fn($it) => [
                'title'      => $it->title,
                'body'       => $it->body,
                'sort_order' => (int) $it->sort_order,
            ])->values()->toArray();

            $this->payments = $this->contract->payments->map(fn($p) => [
                'payment_type' => $p->payment_type,
                'title'        => $p->title,
                'stage'        => $p->stage,
                'condition'    => $p->condition,
                'due_date'     => optional($p->due_date)->format('Y-m-d'),
                'amount'       => $p->amount,
                'include_tax'  => (bool) $p->include_tax,
                'is_paid'      => (bool) $p->is_paid,
                'notes'        => $p->notes,
            ])->values()->toArray();
        } else {
            $this->contract = null;
            $this->items = [];
            $this->payments = [];
        }
    }

    public function render()
    {
        return view('livewire.contracts.create', [
            'contract' => $this->contract,
        ]);
    }

    public function updatedClientId(): void
    {
        $this->project_id = null;
        $this->offer_id = null;
        $this->offers = [];
        $this->loadProjects();
    }

    public function updatedProjectId(): void
    {
        $this->offer_id = null;
        $this->loadOffers();
    }

    protected function loadProjects(): void
    {
        if (!$this->client_id) {
            $this->projects = [];
            return;
        }

        $this->projects = Project::select('id','name')
            ->where('client_id', $this->client_id)
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    protected function loadOffers(): void
    {
        if (!$this->project_id) {
            $this->offers = [];
            return;
        }

        $this->offers = Offer::select('id','start_date')
            ->where('project_id', $this->project_id)
            ->orderByDesc('id')
            ->get()
            ->map(fn($of) => [
                'id' => $of->id,
                'start_date' => optional($of->start_date)->format('Y-m-d'),
            ])->toArray();
    }

    public function addItem(): void
    {
        $this->items[] = [
            'title'      => '',
            'body'       => '',
            'sort_order' => count($this->items),
        ];
    }

    public function removeItem(int $index): void
    {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function addPayment(string $type = 'milestone'): void
    {
        $this->payments[] = [
            'payment_type' => in_array($type, ['milestone','monthly']) ? $type : 'milestone',
            'title'        => '',
            'stage'        => null,
            'condition'    => 'date',
            'due_date'     => null,
            'amount'       => null,
            'include_tax'  => false,
            'is_paid'      => false,
            'notes'        => null,
        ];
    }

    public function removePayment(int $index): void
    {
        if (isset($this->payments[$index])) {
            unset($this->payments[$index]);
            $this->payments = array_values($this->payments);
        }
    }

    public function save()
    {
        $validated = $this->validate();

        $storedPath = null;
        if ($this->contract_file) {
            $storedPath = $this->contract_file->store('contracts');
        }

        DB::beginTransaction();
        try {
            if ($this->contractId) {
                $contract = Contract::lockForUpdate()->findOrFail($this->contractId);
                $contract->fill([
                    'client_id'   => $this->client_id,
                    'project_id'  => $this->project_id,
                    'offer_id'    => $this->offer_id,
                    'type'        => $this->type,
                    'start_date'  => $this->start_date,
                    'end_date'    => $this->end_date,
                    'amount'      => $this->amount,
                    'include_tax' => $this->include_tax,
                    'status'      => $this->status,
                ]);
                if ($storedPath) {
                    $contract->contract_file = $storedPath;
                }
                $contract->save();
            } else {
                $contract = Contract::create([
                    'client_id'   => $this->client_id,
                    'project_id'  => $this->project_id,
                    'offer_id'    => $this->offer_id,
                    'type'        => $this->type,
                    'start_date'  => $this->start_date,
                    'end_date'    => $this->end_date,
                    'amount'      => $this->amount,
                    'include_tax' => $this->include_tax,
                    'contract_file' => $storedPath,
                    'status'      => $this->status,
                ]);
                $this->contractId = $contract->id;
                $this->contract = $contract;
            }

            $contract->items()->delete();
            foreach ($this->items as $i => $it) {
                ContractItem::create([
                    'contract_id' => $contract->id,
                    'title'       => $it['title'] ?? null,
                    'body'        => $it['body']  ?? null,
                    'sort_order'  => $i,
                ]);
            }

            $contract->payments()->delete();
            foreach ($this->payments as $p) {
                ContractPayment::create([
                    'contract_id'  => $contract->id,
                    'payment_type' => $p['payment_type'],
                    'title'        => $p['title'] ?? null,
                    'stage'        => $p['stage'] ?? null,
                    'condition'    => $p['condition'],
                    'due_date'     => $p['due_date'] ?? null,
                    'amount'       => $p['amount'] ?? 0,
                    'include_tax'  => $p['include_tax'],
                    'is_paid'      => $p['is_paid'],
                    'notes'        => $p['notes'] ?? null,
                ]);
            }

            DB::commit();
            session()->flash('success', $this->contractId ? 'تم تحديث العقد بنجاح' : 'تم إنشاء العقد بنجاح');
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($storedPath) {
                try { Storage::delete($storedPath); } catch (\Throwable $t) {}
            }
            session()->flash('error', 'حدث خطأ أثناء حفظ العقد. برجاء المحاولة مرة أخرى.');
            report($e);
        }
    }
}
