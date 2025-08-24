<?php

namespace App\Http\Livewire\Projects;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $confirmingDelete = false;
    public $projectToDelete = null;

    // تحديث نتائج البحث
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // فتح نافذة تأكيد الحذف
    public function confirmDelete($id)
    {
        $this->projectToDelete = $id;
        $this->confirmingDelete = true;
    }

    // تنفيذ الحذف
    public function delete()
    {
        if ($this->projectToDelete) {
            $project = Project::find($this->projectToDelete);

            if ($project) {
                $project->delete();
                session()->flash('success', '✅ تم حذف المشروع بنجاح');
            }

            $this->confirmingDelete = false;
            $this->projectToDelete = null;
            $this->resetPage();
        }
    }

    public function render()
    {
        $projects = Project::with(['client', 'country'])
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';
                $query->where('name', 'like', $search)
                      ->orWhere('description', 'like', $search);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.projects.index', [
            'projects' => $projects,
        ]);
    }
}
