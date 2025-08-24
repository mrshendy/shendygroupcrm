<?php

namespace App\Http\Livewire\Projects;

use App\Models\Client;
use App\Models\Project;
use App\Models\countries;
use Carbon\Carbon;
use Livewire\Component;

class Create extends Component
{
    public $name;
    public $description;
    public $details;
    public $status = 'new';
    public $country_id;
    public $client_id;
    public $project_type;
    public $programming_type;
    public $phase;
    public $start_date;
    public $end_date;
    public $priority = 'medium';
    public $showDeadlineAlert = false;

    /** ✅ قواعد التحقق */
    protected $rules = [
        'name'            => 'required|string|min:3|max:255',
        'description'     => 'required|string|min:5|max:1000',
        'details'         => 'required|string|min:5',
        'status'          => 'required|in:new,in_progress,completed,closed',
        'country_id'      => 'required|exists:countries,id',
        'client_id'       => 'required|exists:clients,id',
        'project_type'    => 'required|string|in:marketing,programming,crm,design,management,training',
        'programming_type'=> 'nullable|string|in:web,system,mobile,api,other',
        'phase'           => 'nullable|string|in:analysis,design,development,testing,deployment',
        'start_date'      => 'required|date',
        'end_date'        => 'required|date|after_or_equal:start_date',
        'priority'        => 'required|in:low,medium,high,critical',
    ];

    /** ✅ رسائل التحقق بالعربي */
    protected $messages = [
        'name.required'            => 'اسم المشروع مطلوب',
        'name.min'                 => 'اسم المشروع يجب أن يكون 3 أحرف على الأقل',
        'name.max'                 => 'اسم المشروع لا يجب أن يتجاوز 255 حرف',
        
        'description.required'     => 'الوصف مطلوب',
        'description.min'          => 'الوصف يجب أن يكون 5 أحرف على الأقل',
        'description.max'          => 'الوصف لا يجب أن يتجاوز 1000 حرف',

        'details.required'         => 'تفاصيل المشروع مطلوبة',
        'details.min'              => 'تفاصيل المشروع يجب أن تحتوي على 5 أحرف على الأقل',

        'status.required'          => 'حالة المشروع مطلوبة',
        'status.in'                => 'حالة المشروع غير صحيحة',

        'country_id.required'      => 'يجب اختيار الدولة',
        'country_id.exists'        => 'الدولة غير موجودة',

        'client_id.required'       => 'يجب اختيار العميل',
        'client_id.exists'         => 'العميل غير موجود',

        'project_type.required'    => 'يجب اختيار نوع المشروع',
        'project_type.in'          => 'نوع المشروع غير صحيح',

        'programming_type.in'      => 'نوع البرمجة غير صحيح',

        'phase.in'                 => 'المرحلة غير صحيحة',

        'start_date.required'      => 'تاريخ البداية مطلوب',
        'start_date.date'          => 'تاريخ البداية غير صحيح',

        'end_date.required'        => 'تاريخ النهاية مطلوب',
        'end_date.date'            => 'تاريخ النهاية غير صحيح',
        'end_date.after_or_equal'  => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية',

        'priority.required'        => 'الأولوية مطلوبة',
        'priority.in'              => 'الأولوية غير صحيحة',
    ];

    public function save()
    {
        $this->validate();

        // تنبيه قرب انتهاء المشروع
        if ($this->end_date && Carbon::parse($this->end_date)->isBefore(now()->addDays(3))) {
            $this->showDeadlineAlert = true;
        }

        Project::create([
            'name'            => $this->name,
            'description'     => $this->description,
            'details'         => $this->details,
            'status'          => $this->status,
            'country_id'      => $this->country_id,
            'client_id'       => $this->client_id,
            'project_type'    => $this->project_type,
            'programming_type'=> $this->project_type === 'programming' ? $this->programming_type : null,
            'phase'           => $this->project_type === 'programming' ? $this->phase : null,
            'start_date'      => $this->start_date,
            'end_date'        => $this->end_date,
            'priority'        => $this->priority,
        ]);

        session()->flash('success', '✅ تمت إضافة المشروع بنجاح');
        return redirect()->route('projects.index');
    }

    public function render()
    {
        return view('livewire.projects.create', [
            'countries' => countries::orderBy('name')->get(),
            'clients'   => Client::orderBy('name')->get(),
        ])->extends('layouts.master')->section('content');
    }
}
