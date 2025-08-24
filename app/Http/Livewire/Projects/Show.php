<?php

namespace App\Http\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;

class Show extends Component
{
    public $project;

     public function mount($id)
    {
        $this->project = Project::with(['client', 'country'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.projects.show', [
            'project' => $this->project,
        ])->extends('layouts.master')->section('content');
    }
}
