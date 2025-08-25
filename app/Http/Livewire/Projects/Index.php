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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // فتح مودال التأكيد
    public function confirmDelete($id)
    {
        $this->projectToDelete = $id;
        $this->confirmingDelete = true;
        $this->dispatchBrowserEvent('open-delete-modal');
    }

    // تنفيذ الحذف
    public function delete()
    {
        if ($this->projectToDelete) {
            $project = Project::find($this->projectToDelete);

            if ($project) {
                $project->delete();
                session()->flash('success', '✅ تم حذف المشروع بنجاح');
            } else {
                session()->flash('error', '⚠️ لم يتم العثور على المشروع.');
            }

            $this->confirmingDelete = false;
            $this->projectToDelete = null;

            // إغلاق المودال وتحديث الجدول
            $this->dispatchBrowserEvent('close-delete-modal');
            $this->resetPage();
        }
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
        ]);
    }
}
