<?php

namespace App\Http\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;

class Edit extends Component
{
    public $clientId;

    // نفس حقول الإنشاء
    public $name;
    public $email;
    public $phone;
    public $status = 'new';

    public $address;
    public $country; // أو country_id لو عندك جدول دول

    // بيانات شخص التواصل
    public $contact_name;
    public $job;
    public $contact_phone;
    public $contact_email;
    public $is_main_contact = false;

    protected function rules()
    {
        return [
            'name'            => 'required|string|max:255',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:50',
            'status'          => 'required|in:new,active,inactive,blocked', // عدّل القيم حسب مشروعك
            'address'         => 'nullable|string|max:500',
            'country'         => 'nullable|string|max:120', // أو exists:countries,id
            'contact_name'    => 'nullable|string|max:255',
            'job'             => 'nullable|string|max:255',
            'contact_phone'   => 'nullable|string|max:50',
            'contact_email'   => 'nullable|email|max:255',
            'is_main_contact' => 'boolean',
        ];
    }

    public function mount($clientId)
    {
        $this->clientId = $clientId;

        $client = Client::findOrFail($clientId);

        // املأ القيم الحالية
        $this->name            = $client->name;
        $this->email           = $client->email;
        $this->phone           = $client->phone;
        $this->status          = $client->status ?? 'new';
        $this->address         = $client->address;
        $this->country         = $client->country; // أو $client->country_id

        $this->contact_name    = $client->contact_name;
        $this->job             = $client->job;
        $this->contact_phone   = $client->contact_phone;
        $this->contact_email   = $client->contact_email;
        $this->is_main_contact = (bool) $client->is_main_contact;
    }

    public function update()
    {
        $data = $this->validate();

        $client = Client::findOrFail($this->clientId);
        $client->update($data);

        // إشعار بسيط + إمكانية إعادة التوجيه
        $this->dispatchBrowserEvent('clientUpdated');
        session()->flash('success', 'تم تحديث بيانات العميل بنجاح.');
        // اختياري: return redirect()->route('clients.show', $client->id);
    }

    public function render()
    {
        return view('livewire.clients.edit');
    }
}
