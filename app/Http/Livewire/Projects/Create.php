<?php

namespace App\Http\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use App\Models\Client;
use App\Models\countries;
use Carbon\Carbon;

class Create extends Component
{
    public $name, $description, $details, $status = 'new';
    public $country_id, $client_id;
    public $project_type, $programming_type, $phase;
    public $start_date, $end_date;
    public $priority = 'medium';

    public $showDeadlineAlert = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'details' => 'nullable|string',
        'status' => 'required|string',
        'country_id' => 'nullable|exists:countries,id',
        'client_id' => 'nullable|exists:clients,id',
        'project_type' => 'required|string|in:marketing,programming,crm,design,management,training',
        'programming_type' => 'nullable|string|in:web,system,mobile,api,other',
        'phase' => 'nullable|string|in:analysis,design,development,testing,deployment',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'priority' => 'nullable|in:low,medium,high,critical',
    ];

    public function save()
    {
        $this->validate();

        // تنبيه قرب انتهاء المشروع
        if ($this->end_date && Carbon::parse($this->end_date)->isBefore(now()->addDays(3))) {
            $this->showDeadlineAlert = true;
        }

        Project::create([
            'name' => $this->name,
            'description' => $this->description,
            'details' => $this->details,
            'status' => $this->status,
            'country_id' => $this->country_id,
            'client_id' => $this->client_id,
            'project_type' => $this->project_type,
            'programming_type' => $this->project_type == 'programming' ? $this->programming_type : null,
            'phase' => $this->project_type == 'programming' ? $this->phase : null,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'priority' => $this->priority,
        ]);

        session()->flash('success', 'تمت إضافة المشروع بنجاح.');
        return redirect()->route('projects.index');
    }

    public function render()
    {
        return view('livewire.projects.create', [
            'countries' => countries::orderBy('name')->get(),
            'clients' => Client::orderBy('name')->get(),
        ])->extends('layouts.master')->section('content');
    }
}