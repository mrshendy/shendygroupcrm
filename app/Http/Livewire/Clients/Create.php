<?php

namespace App\Http\Livewire\Clients;

use App\Models\Client;
use App\Models\Countries;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Create extends Component
{
    public $clients = [];

    public $countries = [];

    public function mount()
    {
        $this->countries = countries::pluck('name')->toArray();

        $this->clients[] = $this->emptyClient('full');
    }

    public function emptyClient($type = 'full')
    {
        $contactFields = [
            'contact_name' => '',
            'contact_job' => '',
            'contact_phone' => '',
            'contact_email' => '',
            'is_primary' => false,
        ];

        if ($type === 'contact-only') {
            return array_merge(['type' => 'contact'], $contactFields);
        }

        return array_merge([
            'type' => 'full',
            'name' => '',
            'email' => '',
            'phone' => '',
            'status' => 'new',
            'address' => '',
            'country' => '',
        ], $contactFields);
    }

    public function addClient()
    {

        $this->clients[] = $this->emptyClient('contact-only');
    }

    public function removeClient($index)
    {
        unset($this->clients[$index]);
        $this->clients = array_values($this->clients);
    }

    public function save()
    {
        foreach ($this->clients as $client) {

            if ($client['type'] !== 'full') {
                continue;
            }

            $rules = [
                'contact_name' => 'required|string|min:3|max:255',
                'contact_job' => 'required|string|min:2|max:100',
                'contact_phone' => 'nullable|string|max:20',
                'contact_email' => 'nullable|email',
                'is_primary' => 'boolean',

                'name' => 'required|string|min:3|max:255',
                'email' => 'nullable|email|unique:clients,email',
                'phone' => 'nullable|string|max:20',
                'status' => 'required|in:new,in_progress,closed',
                'address' => 'required|string|min:5|max:255',
                'country' => 'required|string|min:2|max:100',
            ];

            $validated = Validator::make($client, $rules)->validate();

            Client::create($validated);
        }

        session()->flash('success', '  تمت إضافة العميل بنجاح  ');
        $this->clients = [$this->emptyClient('full')];
    }

    public function render()
    {
        return view('livewire.clients.create')->layout('layouts.master');
    }
}
