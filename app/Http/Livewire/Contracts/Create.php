<?php

namespace App\Http\Livewire\Contracts;

use Livewire\Component;
use App\Models\Client;
use App\Models\Project;
use App\Models\Offer;
use App\Models\Contract;

class Create extends Component
{
    public ?Contract $contract = null;

    public $client_id = null;
    public $project_id = null;
    public $offer_id   = null;

    public $clients  = [];
    public $projects = [];
    public $offers   = [];

    public $type;
    public $start_date;
    public $end_date;
    public $amount;
    public $include_tax = false;
    public $status = 'active';

    public function mount(?Contract $contract = null)
    {
        $this->contract = $contract;

        // جلب العملاء (id, name)
        $this->clients = Client::select('id', 'name')
            ->orderBy('name')
            ->get()
            ->toArray();

        if ($contract) {
            $this->client_id   = $contract->client_id;
            $this->project_id  = $contract->project_id;
            $this->offer_id    = $contract->offer_id;
            $this->type        = $contract->type;
            $this->start_date  = optional($contract->start_date)->format('Y-m-d');
            $this->end_date    = optional($contract->end_date)->format('Y-m-d');
            $this->amount      = $contract->amount;
            $this->include_tax = (bool) $contract->include_tax;
            $this->status      = $contract->status;

            $this->loadProjects();
            $this->loadOffers();
        }
    }

    protected function rules()
    {
        return [
            'client_id'   => 'required|exists:clients,id',
            'project_id'  => 'nullable|exists:projects,id',
            'offer_id'    => 'nullable|exists:offers,id',
            'type'        => 'required|string',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'amount'      => 'required|numeric|min:0',
            'include_tax' => 'boolean',
            'status'      => 'required|in:draft,active,suspended,completed,cancelled',
        ];
    }

    public function updated($property, $value)
    {
        if ($property === 'client_id') {
            $this->project_id = null;
            $this->offer_id   = null;
            $this->offers     = [];
            $this->loadProjects();
        }

        if ($property === 'project_id') {
            $this->offer_id = null;
            $this->loadOffers();
        }
    }

    public function loadProjects()
    {
        if ($this->client_id) {
            $this->projects = Project::where('client_id', $this->client_id)
                ->select('id', 'name')
                ->orderBy('name')
                ->get()
                ->toArray();
        } else {
            $this->projects = [];
        }
    }

    public function loadOffers()
    {
        if ($this->client_id && $this->project_id) {
            $this->offers = Offer::where('client_id', $this->client_id)
                ->where('project_id', $this->project_id)
                ->select('id', 'description as name')
                ->orderBy('id', 'desc')
                ->get()
                ->toArray();
        } elseif ($this->client_id) {
            $this->offers = Offer::where('client_id', $this->client_id)
                ->select('id', 'description as name')
                ->orderBy('id', 'desc')
                ->get()
                ->toArray();
        } else {
            $this->offers = [];
        }
    }

    public function save()
    {
        $data = $this->validate();

        $data['contract_file'] = $data['contract_file'] ?? null;

        if ($this->contract) {
            $this->contract->update($data);
            session()->flash('success', 'تم تحديث العقد بنجاح');
        } else {
            Contract::create($data);
            session()->flash('success', 'تم إنشاء العقد بنجاح');
        }

        $this->dispatch('contractSaved');
    }

    public function render()
    {
        return view('livewire.contracts.create');
    }
}
