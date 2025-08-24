<?php

namespace App\Http\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;
use App\Models\countries; // ✅ لأن اسم الكلاس عندك كده
use Illuminate\Support\Facades\Validator;

class Create extends Component
{
    public $clients = [];
    public $countries = [];

    public function mount()
    {
        // هات الدول (id => name) 
        // لو الاسم عندك Translatable (JSON)، نقدر ناخد بالعربي name->ar
        $this->countries = countries::orderBy('name->ar')->pluck('name', 'id')->toArray();
        

        // أول سجل (عميل كامل)
        $this->clients[] = $this->emptyClient('full');
    }

    private function emptyClient($type = 'full')
    {
        $contactFields = [
            'contact_name'    => '',
            'contact_job'     => '',
            'contact_phone'   => '',
            'contact_email'   => '',
            'is_main_contact' => false,
        ];

        if ($type === 'contact-only') {
            return array_merge(['type' => 'contact-only'], $contactFields);
        }

        return array_merge([
            'type'       => 'full',
            'name'       => '',
            'email'      => '',
            'phone'      => '',
            'status'     => 'new',
            'address'    => '',
            'country_id' => null,   // نخزن ID الدولة
            'job'        => '',
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
            if (($client['type'] ?? '') !== 'full') {
                continue;
            }

            $rules = [
                'name'       => 'required|string|min:3|max:255',
                'email'      => 'nullable|email|unique:clients,email',
                'phone'      => 'nullable|string|max:20',
                'status'     => 'required|in:new,in_progress,active,closed',
                'address'    => 'nullable|string|max:255',
                'country_id' => 'nullable|exists:countries,id', // ✅ التحقق من وجود الدولة
                'job'        => 'nullable|string|max:255',

                'contact_name'    => 'nullable|string|max:255',
                'contact_job'     => 'nullable|string|max:255',
                'contact_phone'   => 'nullable|string|max:50',
                'contact_email'   => 'nullable|email|max:255',
                'is_main_contact' => 'boolean',
            ];

            $validated = Validator::make($client, $rules)->validate();

            Client::create($validated);
        }

        session()->flash('success', 'تمت إضافة العميل بنجاح ✅');
        $this->clients = [$this->emptyClient('full')];
    }

    public function render()
    {
        return view('livewire.clients.create')->layout('layouts.master');
    }
}
