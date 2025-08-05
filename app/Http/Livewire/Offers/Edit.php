<?php

namespace App\Http\Livewire\Offers;

use Livewire\Component;
use App\Models\Offer;
use App\Models\Client;
use App\Models\Project;

class Edit extends Component
{
    public $offer;
    public $clients;
    public $projects;

    public $client_id;
    public $project_id;
    public $start_date;
    public $end_date;
    public $status;
    public $amount;
    public $description;

    public function mount($id)
    {
        $this->offer = Offer::findOrFail($id);

        $this->client_id = $this->offer->client_id;
        $this->project_id = $this->offer->project_id;
        $this->start_date = $this->offer->start_date;
        $this->end_date = $this->offer->end_date;
        $this->status = $this->offer->status;
        $this->amount = $this->offer->amount;
        $this->description = $this->offer->description;

        $this->clients = Client::all();
        $this->projects = Project::where('client_id', $this->client_id)->get();
    }

    public function updatedClientId($value)
    {
        $this->projects = Project::where('client_id', $value)->get();
        $this->project_id = null;
    }

    public function update()
    {
        $this->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,closed,expired',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $this->offer->update([
            'client_id' => $this->client_id,
            'project_id' => $this->project_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'amount' => $this->amount,
            'description' => $this->description,
        ]);

        session()->flash('success', '✅ تم تعديل العرض بنجاح!');
        $this->emit('offerUpdated');
        return redirect()->route('offers.index');
    }

    public function render()
    {
        return view('livewire.offers.edit');
    }
}
