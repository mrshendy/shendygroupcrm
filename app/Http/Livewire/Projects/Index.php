<?php
namespace App\Http\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDelete = false;
    public $projectToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->projectToDelete = Project::findOrFail($id);
        $this->confirmingDelete = true;
    }

    public function deleteProject()
    {
        if ($this->projectToDelete) {
            $this->projectToDelete->delete();
            session()->flash('success', 'تم حذف المشروع بنجاح');
        }

        $this->confirmingDelete = false;
        $this->projectToDelete = null;
    }

    public function render()
    {
        $projects = Project::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('description', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.projects.index', [
            'projects' => $projects,
            'confirmingDelete' => $this->confirmingDelete,
            'projectToDelete' => $this->projectToDelete,
        ]);
    }
}