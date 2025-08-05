<?php

namespace App\Http\Livewire\Offers;

use App\Models\Client;
use App\Models\Offer;
use Livewire\Component;
use Livewire\WithFileUploads;

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

    public $status ;

    public $attachment;

    public function mount()
    {
        $this->clients = Client::all();
    }

    public function updatedClientId($value)
    {
        $this->projects = Client::find($value)?->projects ?? collect();
    }

    public function store()
    {
        $this->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'details' => 'required|string|min:5',
            'amount' => 'required|numeric|min:0',
            'include_tax' => 'boolean',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'status' => 'required|string',
        ]);

        $path = null;
        if ($this->attachment) {
            $path = $this->attachment->store('offers', 'public');
        }

        Offer::create([
            'client_id' => $this->client_id,
            'project_id' => $this->project_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'details' => $this->details,
            'amount' => $this->amount,
            'include_tax' => $this->include_tax,
            'description' => $this->description,
            'status' => $this->status,

            'attachment' => $path,
        ]);

        session()->flash('success', 'تم إنشاء العرض بنجاح.');

        return redirect()->route('offers.index');
    }

    public function render()
    {
        return view('livewire.offers.create');
    }
}
