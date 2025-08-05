<?php

namespace App\Http\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use App\Models\Client;
use App\Models\countries;
use Carbon\Carbon;

class Edit extends Component
{
    public $project;

    public $name, $description, $status, $country_id, $project_type, $programming_type,
           $details, $client_id, $start_date, $end_date, $phase;
public $priority; // ← أضف هذا السطر
    public $showDeadlineAlert = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'details' => 'nullable|string',
        'status' => 'required|string',
        'country_id' => 'nullable|exists:countries,id',
        'project_type' => 'required|string',
        'programming_type' => 'nullable|string',
        'client_id' => 'nullable|exists:clients,id',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'phase' => 'nullable|string',
    ];

    public function mount(Project $project)
    {
        $this->project = $project;

        $this->name = $project->name;
        $this->description = $project->description;
        $this->status = $project->status;
        $this->country_id = $project->country_id;
        $this->project_type = $project->project_type;
        $this->programming_type = $project->programming_type;
        $this->details = $project->details;
        $this->client_id = $project->client_id;
        $this->start_date = $project->start_date;
        $this->end_date = $project->end_date;
        $this->phase = $project->phase;

        // تنبيه إذا كانت المدة المتبقية أقل من 3 أيام
        if ($this->end_date && Carbon::parse($this->end_date)->isBefore(now()->addDays(3))) {
            $this->showDeadlineAlert = true;
        }
    }

    public function update()
    {
        $this->validate();

        $this->project->update([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'country_id' => $this->country_id,
            'project_type' => $this->project_type,
            'programming_type' => $this->project_type == 'programming' ? $this->programming_type : null,
            'details' => $this->details,
            'client_id' => $this->client_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'phase' => $this->project_type == 'programming' ? $this->phase : null,
        ]);

        session()->flash('success', 'تم تحديث المشروع بنجاح.');
        return redirect()->route('projects.index');
    }

    public function render()
    {
        return view('livewire.projects.edit', [
            'countries' => countries::orderBy('name')->get(),
            'clients' => Client::orderBy('name')->get(),
        ])->extends('layouts.master')->section('content');
    }
}