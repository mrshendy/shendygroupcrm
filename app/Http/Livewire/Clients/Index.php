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

    public function confirmDelete($id)
    {
        Client::findOrFail($id)->delete();
        $this->dispatchBrowserEvent('clientDeleted');
        session()->flash('success', 'تم حذف العميل بنجاح');
    }

    public function render()
    {
        $clients = Client::where('name', 'like', "%{$this->search}%")->paginate(10);
        return view('livewire.clients.index', compact('clients'));
    }
}
