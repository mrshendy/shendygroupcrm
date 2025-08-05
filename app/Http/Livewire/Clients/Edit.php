<?php

namespace App\Http\Livewire\Clients;


use Livewire\Component;
use App\Models\Client;
use App\Models\Countries;

class Edit extends Component
{
    public $client;
    public $countries = [];

    public function mount(Client $client)
    {
        $this->client = $client;
        $this->countries = Countries::pluck('name')->toArray();
    }

    public function rules()
    {
        return [
            'client.name'           => 'required|string|min:3|max:255',
            'client.email'          => 'nullable|email',
            'client.phone'          => 'nullable|string|max:20',
            'client.status'         => 'required|in:new,in_progress,closed',
            'client.address'        => 'required|string|min:5|max:255',
            'client.country'        => 'required|string|min:2|max:100',
            'client.contact_name'   => 'required|string|min:3|max:255',
            'client.contact_job'    => 'required|string|min:2|max:100',
            'client.contact_phone'  => 'nullable|string|max:20',
            'client.contact_email'  => 'nullable|email',
            'client.is_primary'     => 'boolean',
        ];
    }

    public function updateClient()
    {
        $this->validate();
        $this->client->save();

        session()->flash('success', 'تم تحديث بيانات العميل بنجاح.');
        return redirect()->route('clients.index');
    }

    public function render()
    {
        return view('livewire.clients.edit')->layout('layouts.master');
    }
}