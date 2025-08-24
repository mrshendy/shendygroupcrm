<?php

namespace App\Http\Livewire\Projects;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // لو بتستخدم Bootstrap للـ pagination

    public $search = '';
    public $confirmingDelete = false;
    public $projectToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /** فتح نافذة تأكيد الحذف */
    public function confirmDelete($id)
    {
        $this->projectToDelete = $id;
        $this->confirmingDelete = true;
    }

    /** حذف المشروع */
    public function deleteProject()
    {
        if ($this->projectToDelete) {
            $project = Project::find($this->projectToDelete);

            if ($project) {
                $project->delete();
                session()->flash('success', 'تم حذف المشروع بنجاح');
            }

            $this->resetPage();
        }

        $this->confirmingDelete = false;
        $this->projectToDelete = null;
    }

    public function render()
    {
        $projects = Project::with(['client', 'country'])
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', $search)
                      ->orWhere('description', 'like', $search);
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.projects.index', [
            'projects' => $projects,
            'confirmingDelete' => $this->confirmingDelete,
            'projectToDelete' => $this->projectToDelete,
        ]);
    }
}
