<?php

namespace App\Http\Livewire\Contracts;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Client;
use App\Models\Project;
use App\Models\Offer;
use App\Models\Contract;
use Carbon\Carbon;

class Edit extends Component
{
    use WithFileUploads;

    public Contract $contract;

    public $client_id;
    public $project_id;
    public $offer_id;
    public $clients = [];
    public $projects = [];
    public $offers = [];
    public $type;
    public $start_date;
    public $end_date;
    public $amount;
    public $include_tax = false;
    public $status = 'active';
    public $contract_file;

    public $items = [];
    public $payments = [];

    protected $rules = [
        'client_id' => 'required|exists:clients,id',
        'project_id' => 'nullable|exists:projects,id',
        'offer_id'   => 'nullable|exists:offers,id',
        'type'       => 'required|string',
        'start_date' => 'nullable|date',
        'end_date'   => 'nullable|date|after_or_equal:start_date',
        'amount'     => 'required|numeric|min:0',
        'include_tax'=> 'boolean',
        'status'     => 'required|string',
        'contract_file' => 'nullable|file|max:5120|mimes:pdf,doc,docx,png,jpg,jpeg',
        'items.*.title' => 'nullable|string|max:255',
        'items.*.body'  => 'nullable|string',
        'payments.*.payment_type' => 'required|string',
        'payments.*.title' => 'nullable|string|max:255',
        'payments.*.stage' => 'nullable|string',
        'payments.*.condition' => 'nullable|string',
        'payments.*.due_date' => 'nullable|date',
        'payments.*.amount' => 'nullable|numeric|min:0',
        'payments.*.include_tax' => 'boolean',
        'payments.*.is_paid' => 'boolean',
        'payments.*.notes' => 'nullable|string',
    ];

    protected $messages = [
        'client_id.required' => 'اختر العميل',
        'type.required' => 'اختر نوع العقد',
        'amount.required' => 'أدخل قيمة الإجمالي',
        'payments.*.payment_type.required' => 'نوع الدفعة مطلوب',
    ];

    public function mount(Contract $contract)
    {
        $this->contract = $contract;

        $this->clients = Client::select('id','name')->orderBy('name')->get()->toArray();

        $this->client_id  = $contract->client_id;
        $this->project_id = $contract->project_id;
        $this->offer_id   = $contract->offer_id;
        $this->type       = $contract->type;
        $this->start_date = optional($contract->start_date)->format('Y-m-d');
        $this->end_date   = optional($contract->end_date)->format('Y-m-d');
        $this->amount     = $contract->amount;
        $this->include_tax= (bool) $contract->include_tax;
        $this->status     = $contract->status;

        $this->projects = Project::where('client_id', $this->client_id)->select('id','name')->get()->toArray();

        if ($this->project_id) {
            $this->offers = Offer::where('project_id', $this->project_id)
                ->select('id','start_date')
                ->get()
                ->map(fn($of)=>['id'=>$of->id,'start_date'=>optional($of->start_date)->format('Y-m-d')])
                ->toArray();
        }

        $this->items = $contract->items()->select('title','body')->get()->toArray();
        $this->payments = $contract->payments()->select(
            'payment_type','title','stage','condition','due_date','amount','include_tax','is_paid','notes'
        )->get()->map(function ($p) {
            return [
                'payment_type' => $p->payment_type,
                'title'        => $p->title,
                'stage'        => $p->stage,
                'condition'    => $p->condition,
                'due_date'     => optional($p->due_date)->format('Y-m-d'),
                'amount'       => $p->amount,
                'include_tax'  => (bool)$p->include_tax,
                'is_paid'      => (bool)$p->is_paid,
                'notes'        => $p->notes,
            ];
        })->toArray();
    }

    public function updatedClientId()
    {
        $this->projects = Project::where('client_id', $this->client_id)->select('id','name')->get()->toArray();
        $this->project_id = null;
        $this->offers = [];
        $this->offer_id = null;
    }

    public function updatedProjectId()
    {
        $this->offers = Offer::where('project_id', $this->project_id)
            ->select('id','start_date')
            ->get()
            ->map(fn($of)=>['id'=>$of->id,'start_date'=>optional($of->start_date)->format('Y-m-d')])
            ->toArray();
        $this->offer_id = null;
    }

    public function addItem()
    {
        $this->items[] = ['title' => '', 'body' => ''];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function addPayment($type = 'milestone')
    {
        $this->payments[] = [
            'payment_type' => $type,
            'title' => '',
            'stage' => '',
            'condition' => 'date',
            'due_date' => '',
            'amount' => 0,
            'include_tax' => false,
            'is_paid' => false,
            'notes' => '',
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

        // تجهيز بيانات العقد فقط
        $contractData = collect($data)->except(['items','payments'])->toArray();

        if ($this->contract_file) {
            $contractData['contract_file'] = $this->contract_file->store('contracts', 'public');
        }

        $this->contract->update($contractData);

        // تحديث البنود
        $this->contract->items()->delete();
        foreach ($this->items as $item) {
            $this->contract->items()->create($item);
        }

        // تحديث الدفعات
        $this->contract->payments()->delete();
        foreach ($this->payments as $pay) {
            $this->contract->payments()->create($pay);
        }

        session()->flash('success', 'تم تحديث العقد بنجاح');
        return redirect()->route('contracts.index');
    }

    public function render()
    {
        return view('livewire.contracts.edit');
    }
}
