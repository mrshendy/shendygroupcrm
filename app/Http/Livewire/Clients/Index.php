<?php

namespace App\Http\Livewire\Clients;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingId = null;

    // الإحصائيات
    public $totalClients = 0;
    public $newClients = 0;
    public $inProgressClients = 0; // قيد التنفيذ
    public $activeClients = 0;
    public $closedClients = 0;     // موقوف/مغلق

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'clientDeleted' => '$refresh',
    ];

    public function mount()
    {
        $this->calcStats();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    private function calcStats()
    {
        // لو عندك قيم مختلفة للحالة، عدّلها هنا
        $this->totalClients      = Client::count();
        $this->newClients        = Client::where('status', 'new')->count();

        // دعم تهجئة مختلفة لقيد التنفيذ
        $this->inProgressClients = Client::whereIn('status', ['under_implementation', 'Under implementation', 'in_progress'])->count();

        $this->activeClients     = Client::where('status', 'active')->count();

        // “closed” كموقوف/مغلق
        $this->closedClients     = Client::whereIn('status', ['closed', 'blocked', 'suspended'])->count();
    }

    public function confirmDelete($id)
    {
        $this->confirmingId = $id;
        $this->dispatchBrowserEvent('showDeleteModal');
    }

    public function deleteClient()
    {
        if ($this->confirmingId) {
            $client = Client::find($this->confirmingId);
            if ($client) {
                $client->delete();
                $this->confirmingId = null;
                $this->calcStats(); // حدّث الإحصائيات بعد الحذف
                $this->dispatchBrowserEvent('toast', ['type' => 'success', 'msg' => 'تم حذف العميل.']);
                $this->emit('clientDeleted');
            }
        }
    }

    public function render()
    {
        $clients = Client::query()
            ->when($this->search, function ($q) {
                $s = '%'.$this->search.'%';
                $q->where(function ($qq) use ($s) {
                    $qq->where('name', 'like', $s)
                       ->orWhere('email', 'like', $s)
                       ->orWhere('phone', 'like', $s);
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.clients.index', compact('clients'));
    }
}
