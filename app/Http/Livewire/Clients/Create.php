<?php

namespace App\Http\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;
use App\Models\Countries; // ← زي ما أنت مستوردها
use Illuminate\Support\Facades\Validator;

class Create extends Component
{
    public $clients = [];
    public $countries = [];

    public function mount()
    {
        // لو جدول countries فيه عمود name:
        $this->countries = Countries::orderBy('name')->pluck('name')->toArray();

        // أول سطر (ملف عميل كامل)
        $this->clients[] = $this->emptyClient('full');
    }

    public function emptyClient($type = 'full')
    {
        $contactFields = [
            'contact_name'   => '',
            'contact_job'    => '',
            'contact_phone'  => '',
            'contact_email'  => '',
            'is_main_contact'=> false, // ← بدلاً من is_primary
        ];

        if ($type === 'contact-only') {
            return array_merge(['type' => 'contact'], $contactFields);
        }

        return array_merge([
            'type'    => 'full',
            'name'    => '',
            'email'   => '',
            'phone'   => '',
            'status'  => 'new', // تأكد إنها match لقيمك في DB (مثلاً active/inactive..)
            'address' => '',
            'country' => '',    // اسم الدولة كنص (لو عندك country_id بدّل القاعدة)
        ], $contactFields);
    }

    public function addClient()
    {
        // صفّ اتصال إضافي (لو عايز تضيف أكثر من جهة اتصال في نفس الفورم)
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
            // إحفظ فقط الـ rows من نوع full (الـ contact-only مش محفوظة هنا)
            if (($client['type'] ?? '') !== 'full') {
                continue;
            }

            $rules = [
                // بيانات الاتصال
                'contact_name'    => 'required|string|min:3|max:255',
                'contact_job'     => 'required|string|min:2|max:100',
                'contact_phone'   => 'nullable|string|max:20',
                'contact_email'   => 'nullable|email',
                'is_main_contact' => 'boolean',

                // بيانات العميل
                'name'    => 'required|string|min:3|max:255',
                'email'   => 'nullable|email|unique:clients,email',
                'phone'   => 'nullable|string|max:20',
                'status'  => 'required|in:new,Under implementation,closed', // راجع القيم مع جدولك
                'address' => 'required|string|min:5|max:255',
                'country' => 'required|string|min:2|max:100',      // لو عندك country_id بدّلها
            ];

            $validated = Validator::make($client, $rules)->validate();

            // تأكد إن الـfillable في Client يغطي الحقول دي
            Client::create($validated);
        }

        session()->flash('success', 'تمت إضافة العميل بنجاح.');
        // إعادة تهيئة النموذج لصفّ واحد "full" جديد
        $this->clients = [$this->emptyClient('full')];
    }

    public function render()
    {
        // لازم يكون عندك الملف: resources/views/livewire/clients/create.blade.php
        return view('livewire.clients.create')->layout('layouts.master');
    }
}
