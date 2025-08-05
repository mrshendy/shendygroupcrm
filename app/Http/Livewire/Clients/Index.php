<?php
namespace App\Http\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
public function confirmDelete($id)
{
    Client::findOrFail($id)->delete();
    session()->flash('success', 'تم حذف العميل بنجاح');
}

    public function render()
    {
        $clients = Client::where('name', 'like', '%'.$this->search.'%')->paginate(10);
        return view('livewire.clients.index', compact('clients'));
    }
}