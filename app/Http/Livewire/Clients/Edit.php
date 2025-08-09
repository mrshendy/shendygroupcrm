<?php

namespace App\Http\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;

class Edit extends Component
{
    public Client $client;     // الموديل الجاي من الراوت
    public array $form = [];   // نموذج وسيط لعرض/تعديل البيانات

    public function mount(Client $client): void
    {
        $this->client = $client;

        // حمّل القيم في الفورم (عشان تظهر في الـ inputs)
        $this->form = [
            'name'    => $client->name,
            'email'   => $client->email,
            'phone'   => $client->phone,
            'status'  => $client->status,   // قيمتك: new | in_progress | closed
            'address' => $client->address,
        ];
    }

    public function update()
    {
        $this->validate([
            'form.name'    => 'required|string|max:255',
            'form.email'   => 'nullable|email|unique:clients,email,' . $this->client->id,
            'form.phone'   => 'nullable|string|max:20',
            'form.status'  => 'required|in:new,in_progress,closed',
            'form.address' => 'nullable|string|max:255',
            'form.country' => 'nullable|string|max:100',
        ]);

        // مرّر القيم من الفورم إلى الموديل واحفظ
        $this->client->fill($this->form)->save();

        $this->dispatchBrowserEvent('clientUpdated');
        session()->flash('success', 'تم تعديل بيانات العميل بنجاح');
        return redirect()->route('clients.index');
    }

    public function render()
    {
        return view('livewire.clients.edit')->layout('layouts.master');
    }
}
