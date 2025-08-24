<?php

namespace App\Http\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;
use App\Models\countries;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $clients = [];
    public $countries = [];

    /** قواعد التحقق */
    protected $rules = [
        'clients.*.name'       => 'required|string|min:3|max:255',
        'clients.*.email'      => 'required|email|unique:clients,email',
        'clients.*.phone'      => 'required|string|max:20',
        'clients.*.status'     => 'required|in:new,in_progress,active,closed',
        'clients.*.address'    => 'required|string|max:255',
        'clients.*.country_id' => 'required|exists:countries,id',
        'clients.*.job'        => 'required|string|max:255',

        'clients.*.contact_name'    => 'required|string|max:255',
        'clients.*.contact_job'     => 'required|string|max:255',
        'clients.*.contact_phone'   => 'required|string|max:50',
        'clients.*.contact_email'   => 'required|email|max:255',
        'clients.*.is_main_contact' => 'boolean',
    ];

    /** رسائل التحقق بالعربي */
    protected $messages = [
        'clients.*.name.required' => 'اسم العميل مطلوب',
        'clients.*.name.min'      => 'اسم العميل يجب أن يكون 3 أحرف على الأقل',
        'clients.*.email.required'=> 'البريد الإلكتروني مطلوب',
        'clients.*.email.email'   => 'صيغة البريد غير صحيحة',
        'clients.*.email.unique'  => 'هذا البريد مستخدم من قبل',
        'clients.*.phone.required'=> 'رقم الهاتف مطلوب',
        'clients.*.status.required'=> 'حالة العميل مطلوبة',
        'clients.*.address.required'=> 'العنوان مطلوب',
        'clients.*.country_id.required'=> 'يجب اختيار الدولة',
        'clients.*.country_id.exists' => 'الدولة غير صحيحة',
        'clients.*.job.required'      => 'المسمى الوظيفي مطلوب',

        'clients.*.contact_name.required'  => 'اسم جهة الاتصال مطلوب',
        'clients.*.contact_job.required'   => 'وظيفة جهة الاتصال مطلوبة',
        'clients.*.contact_phone.required' => 'هاتف جهة الاتصال مطلوب',
        'clients.*.contact_email.required' => 'البريد الإلكتروني لجهة الاتصال مطلوب',
        'clients.*.contact_email.email'    => 'صيغة البريد لجهة الاتصال غير صحيحة',
    ];

    public function mount()
    {
        // ✅ استخدام JSON_EXTRACT للحصول على الاسم العربي
        $this->countries = countries::select(
                'id',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar')) as name_ar")
            )
            ->orderBy('name_ar')
            ->pluck('name_ar', 'id')
            ->toArray();

        // أول سجل
        $this->clients[] = $this->emptyClient();
    }

    private function emptyClient()
    {
        return [
            'type'       => 'full',
            'name'       => '',
            'email'      => '',
            'phone'      => '',
            'status'     => 'new',
            'address'    => '',
            'country_id' => null,
            'job'        => '',

            'contact_name'    => '',
            'contact_job'     => '',
            'contact_phone'   => '',
            'contact_email'   => '',
            'is_main_contact' => false,
        ];
    }

    public function addClient()
    {
        $this->clients[] = $this->emptyClient();
    }

    public function removeClient($index)
    {
        unset($this->clients[$index]);
        $this->clients = array_values($this->clients);
    }

    public function save()
    {
        $this->validate(); // ✅ Livewire هيتكفل بعرض الرسائل مع @error

        foreach ($this->clients as $client) {
            Client::create($client);
        }

        session()->flash('success', '✅ تم إضافة العميل بنجاح');
        $this->clients = [$this->emptyClient()];
    }

    public function render()
    {
        return view('livewire.clients.create')->layout('layouts.master');
    }
}
