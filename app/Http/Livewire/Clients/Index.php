<?php

namespace App\Http\Livewire\Clients;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // علشان شكل الـ pagination يطلع Bootstrap

    public $search = '';

    protected $listeners = [
        'deleteConfirmed' => 'deleteClient', // لو هتستخدم SweetAlert أو Confirm Modal
    ];

    // دالة حذف العميل
    public function deleteClient($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        session()->flash('success', 'تم حذف العميل بنجاح.');
        $this->resetPage(); // علشان لو انت فى صفحة تانية ترجع للأولى بعد الحذف
    }

    public function updatingSearch()
    {
        // علشان أول ما تغيّر البحث يبدأ من الصفحة الأولى
        $this->resetPage();
    }

    public function render()
    {
        $clients = Client::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.clients.index', [
            'clients' => $clients
        ]);
    }
}
