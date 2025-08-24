<?php

namespace App\Http\Livewire\Clients;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $confirmingId = null;

    // إحصائيات
    public $totalClients, $newClients, $inProgressClients, $activeClients, $closedClients;

    protected $listeners = [
        'clientDeleted' => '$refresh',
        'deleteClientConfirmed' => 'deleteClient', // عشان يشتغل مع SweetAlert
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
        $this->totalClients      = Client::count();
        $this->newClients        = Client::where('status', 'new')->count();
        $this->inProgressClients = Client::whereIn('status', ['in_progress','under_implementation'])->count();
        $this->activeClients     = Client::where('status', 'active')->count();
        $this->closedClients     = Client::whereIn('status', ['closed','blocked'])->count();
    }

    public function confirmDelete($id)
    {
        $this->confirmingId = $id;
        $this->dispatchBrowserEvent('showDeleteConfirm'); 
    }

    public function deleteClient()
    {
        if ($this->confirmingId) {
            $client = Client::find($this->confirmingId);
            if ($client) {
                $client->delete();
                $this->confirmingId = null;

                // تحديث الإحصائيات
                $this->calcStats();

                // إشعار للواجهة
                $this->emit('clientDeleted');
                $this->dispatchBrowserEvent('hideDeleteConfirm');
            }
        }
    }

    public function render()
    {
        $clients = Client::query()
            ->when($this->search, function ($q) {
                $s = "%{$this->search}%";
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
