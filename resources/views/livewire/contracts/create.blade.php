<div class="" dir="rtl">
    {{-- عنوان الصفحة: بيتبدّل حسب وجود كائن عقد جاهز للتحرير ولا إنشاء جديد --}}
    <h3 class="mb-3">{{ $contract ? 'تعديل عقد' : 'إنشاء عقد جديد' }}</h3>

    {{-- رسائل فلاش للنجاح/الخطأ جاية من السيشن بعد تنفيذ الحفظ في Livewire --}}
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="mdi mdi-check-circle-outline me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="mdi mdi-alert-circle-outline me-2"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    {{-- فورم الحفظ: بنمنع الإرسال الافتراضي وندعو ميثود save في الكومبوننت --}}
    <form wire:submit.prevent="save" id="contractForm">
        @csrf

        {{-- بيانات العقد الأساسية --}}
        <div class="card mb-4">
            <div class="card-header fw-bold">بيانات العقد</div>
            <div class="card-body row g-3">
                {{-- اختيار العميل: عند تغييره بنحدّث المشاريع تلقائي (المصفوفة $projects) --}}
                <div class="col-md-4">
                    <label class="form-label">العميل <span class="text-danger">*</span></label>
                    <select class="form-select" wire:model.live="client_id">
                        <option value="">اختر</option>
                        @foreach($clients as $cl)
                            <option value="{{ $cl['id'] }}">{{ $cl['name'] }}</option>
                        @endforeach
                    </select>
                    @error('client_id') <small class="text-danger">{{ $message }}</small> @enderror
                    {{-- لودينج أثناء تغيير العميل لتحميل المشاريع --}}
                    <div wire:loading wire:target="client_id" class="small text-muted mt-1">جاري تحميل المشاريع…</div>
                </div>

                {{-- اختيار المشروع: بيتفعل بعد تحميل $projects بناءً على العميل --}}
                <div class="col-md-4">
                    <label class="form-label">المشروع</label>
                    <select class="form-select" wire:model.live="project_id" @disabled(empty($projects))>
                        <option value="">—</option>
                        @foreach($projects as $pr)
                            <option value="{{ $pr['id'] }}">{{ $pr['name'] }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <small class="text-danger">{{ $message }}</small> @enderror
                    {{-- لودينج أثناء تغيير المشروع لتحميل العروض --}}
                    <div wire:loading wire:target="project_id" class="small text-muted mt-1">جاري تحميل العروض…</div>
                </div>

                {{-- اختيار العرض: بيتفعل بعد تحميل $offers بناءً على المشروع --}}
                <div class="col-md-4">
                    <label class="form-label">العرض</label>
                    <select class="form-select" wire:model.live="offer_id" @disabled(empty($offers))>
                        <option value="">—</option>
                        @foreach($offers as $of)
                            <option value="{{ $of['id'] }}">
                                عرض رقم #{{ $of['id'] }}@if(!empty($of['start_date'])) — {{ $of['start_date'] }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('offer_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- نوع العقد: القيم معروضة من ثابت TYPES داخل الموديل --}}
                <div class="col-md-4">
                    <label class="form-label">نوع العقد <span class="text-danger">*</span></label>
                    <select class="form-select" wire:model="type">
                        @foreach(\App\Models\Contract::TYPES as $k=>$v)
                            <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('type') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- بداية العقد (اختياري) --}}
                <div class="col-md-4">
                    <label class="form-label">بداية العقد</label>
                    <input type="date" class="form-control" wire:model="start_date">
                    @error('start_date') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- نهاية العقد (اختياري) --}}
                <div class="col-md-4">
                    <label class="form-label">نهاية العقد</label>
                    <input type="date" class="form-control" wire:model="end_date">
                    @error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- إجمالي قيمة العقد: مطلوب --}}
                <div class="col-md-4">
                    <label class="form-label">الإجمالي <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control" wire:model="amount">
                    @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- هل الإجمالي شامل ضريبة؟ مربوط بفلاغ include_tax --}}
                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="incTax" wire:model="include_tax">
                        <label for="incTax" class="form-check-label">شامل الضريبة</label>
                    </div>
                </div>

                {{-- رفع ملف العقد: يقبل PDF/Word/صور – الرفع يتم عبر Livewire --}}
                <div class="col-md-4">
                    <label class="form-label">ملف العقد</label>
                    <input type="file" class="form-control" wire:model="contract_file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                    @error('contract_file') <small class="text-danger">{{ $message }}</small> @enderror
                    {{-- لودينج أثناء الرفع --}}
                    <div wire:loading wire:target="contract_file" class="small text-muted mt-1">جاري رفع الملف…</div>
                </div>

                {{-- حالة العقد: قيم ثابتة للاختيار --}}
                <div class="col-md-4">
                    <label class="form-label">الحالة</label>
                    <select class="form-select" wire:model="status">
                        <option value="draft">مسودة</option>
                        <option value="active">ساري</option>
                        <option value="suspended">موقوف</option>
                        <option value="completed">مكتمل</option>
                        <option value="cancelled">ملغي</option>
                    </select>
                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        {{-- بنود العقد: تكرار ديناميكي لعناصر البنود المخزّنة في مصفوفة $items --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">بنود العقد</span>
                {{-- زر إضافة عنصر جديد في المصفوفة عبر الميثود addItem --}}
                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addItem">
                    <i class="mdi mdi-plus-circle-outline me-1"></i> إضافة بند
                </button>
            </div>
            <div class="card-body">
                {{-- عرض البنود الحالية؛ لو فاضية نظهر رسالة افتراضية --}}
                @forelse($items as $idx => $it)
                    {{-- wire:key لتحسين أداء إعادة الرسم لكل سطر بند --}}
                    <div class="row g-2 align-items-start mb-2" wire:key="item-{{ $idx }}">
                        {{-- عنوان البند --}}
                        <div class="col-md-3">
                            <input class="form-control" placeholder="عنوان" wire:model.defer="items.{{ $idx }}.title">
                            @error("items.$idx.title") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        {{-- نص/وصف البند --}}
                        <div class="col-md-8">
                            <textarea class="form-control" rows="2" placeholder="النص" wire:model.defer="items.{{ $idx }}.body"></textarea>
                            @error("items.$idx.body") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        {{-- حذف البند الحالي باستدعاء removeItem وتمرير الإندكس --}}
                        <div class="col-md-1 d-flex align-items-center">
                            <button class="btn btn-outline-danger" type="button" wire:click="removeItem({{ $idx }})">حذف</button>
                        </div>
                    </div>
                @empty
                    <div class="text-muted">لا توجد بنود بعد.</div>
                @endforelse
            </div>
        </div>

        {{-- دفعات العقد: تكرار ديناميكي لعناصر $payments ويشمل نوع/شرط/تاريخ/مبلغ/ملاحظات --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">دفعات العقد</span>
                <div class="d-flex gap-2">
                    {{-- إضافة دفعة مرحلية --}}
                    <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addPayment('milestone')">
                        <i class="mdi mdi-plus-circle-outline me-1"></i> إضافة دفعة مرحلية
                    </button>
                    {{-- إضافة دفعة شهرية --}}
                    <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="addPayment('monthly')">
                        <i class="mdi mdi-plus-circle-outline me-1"></i> إضافة دفعة شهرية
                    </button>
                </div>
            </div>
            <div class="card-body">
                {{-- نكرّر على كل دفعة مع key ثابت لتحسين الأداء --}}
                @forelse($payments as $pidx => $p)
                    <div class="row g-2 payment-row border rounded p-2 mb-3" wire:key="pay-{{ $pidx }}">
                        {{-- نوع الدفعة: مرحلية/شهرية (يؤثر على إظهار المرحلة) --}}
                        <div class="col-md-2">
                            <label class="form-label small">النوع</label>
                            <select class="form-select" wire:model.live="payments.{{ $pidx }}.payment_type">
                                <option value="milestone">مرحلية</option>
                                <option value="monthly">شهرية</option>
                            </select>
                            @error("payments.$pidx.payment_type") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- عنوان الدفعة (وصف قصير) --}}
                        <div class="col-md-2">
                            <label class="form-label small">العنوان</label>
                            <input class="form-control" wire:model.defer="payments.{{ $pidx }}.title" placeholder="عنوان">
                            @error("payments.$pidx.title") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- مرحلة التنفيذ (تظهر فقط لو النوع مرحلي) --}}
                        <div class="col-md-2">
                            <label class="form-label small">المرحلة</label>
                            <select class="form-select" wire:model.defer="payments.{{ $pidx }}.stage" @disabled($payments[$pidx]['payment_type'] !== 'milestone')>
                                <option value="">—</option>
                                @foreach(\App\Models\ContractPayment::STAGES as $k=>$v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                            @error("payments.$pidx.stage") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- شرط الاستحقاق: بتاريخ مباشر أو مرتبط بمرحلة --}}
                        <div class="col-md-2">
                            <label class="form-label small">الشرط</label>
                            <select class="form-select" wire:model.defer="payments.{{ $pidx }}.condition">
                                <option value="date">تاريخ</option>
                                <option value="stage">مرحلة</option>
                            </select>
                            @error("payments.$pidx.condition") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- تاريخ الاستحقاق: في حالة الشرط = تاريخ (أو يستخدم مرجعياً للمرحلة) --}}
                        <div class="col-md-2">
                            <label class="form-label small">تاريخ الاستحقاق</label>
                            <input type="date" class="form-control" wire:model.defer="payments.{{ $pidx }}.due_date">
                            @error("payments.$pidx.due_date") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- مبلغ الدفعة --}}
                        <div class="col-md-2">
                            <label class="form-label small">المبلغ</label>
                            <input type="number" step="0.01" class="form-control" wire:model.defer="payments.{{ $pidx }}.amount" placeholder="المبلغ">
                            @error("payments.$pidx.amount") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- هل المبلغ شامل ضريبة؟ --}}
                        <div class="col-md-2">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" wire:model.defer="payments.{{ $pidx }}.include_tax" id="p{{ $pidx }}inc">
                                <label for="p{{ $pidx }}inc" class="form-check-label">شامل</label>
                            </div>
                        </div>

                        {{-- هل الدفعة مدفوعة؟ تستخدم عند تسوية لاحقة --}}
                        <div class="col-md-2">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" wire:model.defer="payments.{{ $pidx }}.is_paid" id="p{{ $pidx }}paid">
                                <label for="p{{ $pidx }}paid" class="form-check-label">مدفوعة</label>
                            </div>
                        </div>

                        {{-- ملاحظات إضافية على الدفعة --}}
                        <div class="col-md-6">
                            <label class="form-label small">ملاحظات</label>
                            <input class="form-control" wire:model.defer="payments.{{ $pidx }}.notes" placeholder="ملاحظات">
                            @error("payments.$pidx.notes") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- حذف الدفعة الحالية --}}
                        <div class="col-12 text-end">
                            <button class="btn btn-outline-danger btn-sm" type="button" wire:click="removePayment({{ $pidx }})">
                                حذف الدفعة
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-muted">لا توجد دفعات بعد.</div>
                @endforelse
            </div>
        </div>

        {{-- زر الحفظ: أثناء التنفيذ بنعطّل الزر ونبدّل النص للودينج --}}
        <div class="text-end">
            <button class="btn btn-success px-4" wire:loading.attr="disabled">
                <span wire:loading.remove>حفظ العقد</span>
                <span wire:loading>جاري الحفظ…</span>
            </button>
        </div>
    </form>
</div>
