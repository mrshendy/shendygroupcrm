<?php

namespace App\Http\Livewire\Contracts;

use App\Models\Client;
use App\Models\Contract;
use App\Models\ContractItem;
use App\Models\ContractPayment;
use App\Models\Offer;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    // حقول العقد
    public $client_id, $project_id, $offer_id;
    public $type = 'software';
    public $start_date, $end_date, $amount = 0, $include_tax = false, $status = 'active';
    public $contract_file;

    // بنود/دفعات
    public $items = [
        ['title' => '', 'body' => '', 'sort_order' => 1],
    ];
    public $payments = [
        ['payment_type'=>'milestone','title'=>'','stage'=>null,'condition'=>'date','period_month'=>null,'due_date'=>null,'amount'=>0,'include_tax'=>false,'is_paid'=>false,'notes'=>null],
    ];

    // قوائم
    public $clients = [];
    public $projects = [];
    public $offers   = [];

    protected function rules()
    {
        $validTypes = array_keys(Contract::TYPES ?? []);
        return [
            'client_id'   => 'required|exists:clients,id',
            'project_id'  => 'nullable|exists:projects,id',
            'offer_id'    => 'nullable|exists:offers,id',
            'type'        => ['required', Rule::in($validTypes ?: ['software'])],
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'amount'      => 'required|numeric|min:0',
            'include_tax' => 'boolean',
            'status'      => 'nullable|in:draft,active,suspended,completed,cancelled',
            'contract_file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:8192',

            'items'                 => 'nullable|array',
            'items.*.title'         => 'required_with:items|string|max:255',
            'items.*.body'          => 'nullable|string',
            'items.*.sort_order'    => 'nullable|integer|min:1',

            'payments'                      => 'nullable|array',
            'payments.*.payment_type'       => 'required_with:payments|in:milestone,monthly',
            'payments.*.title'              => 'nullable|string|max:255',
            'payments.*.stage'              => 'nullable|in:contract,supply,training,operation,migration,soft_live,maintenance',
            'payments.*.condition'          => 'nullable|in:date,stage',
            'payments.*.period_month'       => 'nullable|date',
            'payments.*.due_date'           => 'nullable|date',
            'payments.*.amount'             => 'nullable|numeric|min:0',
            'payments.*.include_tax'        => 'nullable|boolean',
            'payments.*.is_paid'            => 'nullable|boolean',
            'payments.*.notes'              => 'nullable|string|max:500',
        ];
    }

    public function mount(Contract $contract): void
    {
        $this->clients = Client::orderBy('name')->get(['id','name'])->toArray();
            $this->contract = $contract->load(['client','project','offer','items','payments']);

    }

    // hook عام عشان snake_case
    public function updated($name, $value): void
    {
        if ($name === 'client_id') {
            $this->projects = Project::where('client_id',$value)->orderBy('name')->get(['id','name'])->toArray();
            $this->offers   = Offer::where('client_id',$value)->orderByDesc('id')->get(['id','amount','include_tax'])->toArray();
            $this->project_id = $this->offer_id = null;
        }

        if ($name === 'offer_id' && $value) {
            $offer = Offer::find($value);
            if ($offer) { $this->amount = $offer->amount; $this->include_tax = (bool)$offer->include_tax; }
        }
    }

    public function addItem(): void
    { $this->items[] = ['title'=>'','body'=>'','sort_order'=>count($this->items)+1]; }

    public function removeItem(int $i): void
    { unset($this->items[$i]); $this->items = array_values($this->items); }

    public function addPayment(string $type='milestone'): void
    {
        $this->payments[] = [
            'payment_type'=>$type,'title'=>'','stage'=>null,'condition'=>'date',
            'period_month'=>null,'due_date'=>null,'amount'=>0,'include_tax'=>false,'is_paid'=>false,'notes'=>null
        ];
    }

    public function removePayment(int $i): void
    { unset($this->payments[$i]); $this->payments = array_values($this->payments); }

    public function save()
    {
        $this->validate();

        $contract = DB::transaction(function () {
            $c = Contract::create([
                'client_id'   => $this->client_id,
                'project_id'  => $this->project_id,
                'offer_id'    => $this->offer_id,
                'type'        => $this->type,
                'start_date'  => $this->start_date,
                'end_date'    => $this->end_date,
                'amount'      => $this->amount,
                'include_tax' => (bool)$this->include_tax,
                'status'      => $this->status ?? 'active',
            ]);

            if ($this->contract_file) {
                $path = $this->contract_file->store('contracts','public');
                $c->update(['contract_file'=>$path]);
            }

            foreach ($this->items as $i => $row) {
                if (!trim($row['title'])) continue;
                ContractItem::create([
                    'contract_id'=>$c->id,
                    'title'=>$row['title'],
                    'body'=>$row['body'] ?? null,
                    'sort_order'=>$row['sort_order'] ?? ($i+1),
                ]);
            }

            foreach ($this->payments as $p) {
    $pm = !empty($p['period_month']) ? ($p['period_month'].'-01') : null;

    $this->contract->payments()->create([
        'payment_type' => $p['payment_type'] ?? 'milestone',
        'title'        => $p['title'] ?? null,
        'stage'        => $p['stage'] ?? null,
        'condition'    => $p['condition'] ?? 'date',
        'period_month' => $pm,                 // <-- تم التطبيع
        'due_date'     => $p['due_date'] ?? null,
        'amount'       => $p['amount'] ?? 0,
        'include_tax'  => !empty($p['include_tax']),
        'is_paid'      => !empty($p['is_paid']),
        'notes'        => $p['notes'] ?? null,
    ]);
}


            return $c;
        });

        session()->flash('success','تم إنشاء العقد بنجاح.');
        return redirect()->route('contracts.show', $contract->id);
    }

    public function render()
    {
        return view('livewire.contracts.create', [
            'types'  => Contract::TYPES ?? [],
            'stages' => ContractPayment::STAGES ?? [],
        ])->layout('layouts.master', ['title' => 'إنشاء عقد']);
    }
}
