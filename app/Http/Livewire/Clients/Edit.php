<?php

namespace App\Http\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;
use App\Models\countries;

class Edit extends Component
{
    public $clientId;

    public $name, $email, $phone, $status = 'new';
    public $address, $country_id;

    public $contact_name, $contact_job, $job;
    public $contact_phone, $contact_email;

    public $is_primary = false;
    public $is_main_contact = false;

    public $countries = []; // 👈 لعرض الدول في الـ select

    protected function rules()
    {
        return [
            'name'            => 'required|string|max:255',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:50',
            'status'          => 'required|in:new,in_progress,active,closed',
            'address'         => 'nullable|string|max:255',
            'country_id'      => 'nullable|exists:countries,id',

            'contact_name'    => 'nullable|string|max:255',
            'contact_job'     => 'nullable|string|max:255',
            'job'             => 'nullable|string|max:255',

            'contact_phone'   => 'nullable|string|max:50',
            'contact_email'   => 'nullable|email|max:255',

            'is_primary'      => 'boolean',
            'is_main_contact' => 'boolean',
        ];
    }

    public function mount($clientId)
    {
        $this->clientId = $clientId;
        $client = Client::findOrFail($clientId);

        // جلب قائمة الدول
        $this->countries = countries::orderBy('name->ar')->pluck('name', 'id')->toArray();

        // تعبئة الحقول
        $this->name            = $client->name;
        $this->email           = $client->email;
        $this->phone           = $client->phone;
        $this->status          = $client->status;

        $this->address         = $client->address;
        $this->country_id      = $client->country_id;

        $this->contact_name    = $client->contact_name;
        $this->contact_job     = $client->contact_job;
        $this->job             = $client->job;

        $this->contact_phone   = $client->contact_phone;
        $this->contact_email   = $client->contact_email;

        $this->is_primary      = (bool) $client->is_primary;
        $this->is_main_contact = (bool) $client->is_main_contact;
    }

    public function update()
    {
        $data = $this->validate();

        $client = Client::findOrFail($this->clientId);
        $client->update($data);

        session()->flash('success', 'تم تحديث بيانات العميل بنجاح ✅');
        return redirect()->route('clients.index');
    }

    public function render()
    {
        return view('livewire.clients.edit');
    }
}
