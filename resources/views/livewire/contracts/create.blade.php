<div class="container" dir="rtl">
    <h3 class="mb-3">{{ $contract ? 'تعديل عقد' : 'إنشاء عقد جديد' }}</h3>

    {{-- رسائل فلاش --}}
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

    <form wire:submit.prevent="save" id="contractForm">
        @csrf

        {{-- بيانات العقد --}}
        <div class="card mb-4">
            <div class="card-header fw-bold">بيانات العقد</div>
            <div class="card-body row g-3">
                {{-- العميل --}}
                <div class="col-md-4">
                    <label class="form-label">العميل <span class="text-danger">*</span></label>
                    <select class="form-select" wire:model.live="client_id">
                        <option value="">اختر</option>
                        @foreach($clients as $cl)
                            <option value="{{ $cl['id'] }}">{{ $cl['name'] }}</option>
                        @endforeach
                    </select>
                    @error('client_id') <small class="text-danger">{{ $message }}</small> @enderror
                    <div wire:loading wire:target="client_id" class="small text-muted mt-1">جاري تحميل المشاريع…</div>
                </div>

                {{-- المشروع --}}
                <div class="col-md-4">
                    <label class="form-label">المشروع</label>
                    <select class="form-select" wire:model.live="project_id" @disabled(empty($projects))>
                        <option value="">—</option>
                        @foreach($projects as $pr)
                            <option value="{{ $pr['id'] }}">{{ $pr['name'] }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <small class="text-danger">{{ $message }}</small> @enderror
                    <div wire:loading wire:target="project_id" class="small text-muted mt-1">جاري تحميل العروض…</div>
                </div>

         {{-- العرض --}}
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

                {{-- نوع العقد --}}
                <div class="col-md-4">
                    <label class="form-label">نوع العقد <span class="text-danger">*</span></label>
                    <select class="form-select" wire:model="type">
                        @foreach(\App\Models\Contract::TYPES as $k=>$v)
                            <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('type') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- بداية العقد --}}
                <div class="col-md-4">
                    <label class="form-label">بداية العقد</label>
                    <input type="date" class="form-control" wire:model="start_date">
                    @error('start_date') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- نهاية العقد --}}
                <div class="col-md-4">
                    <label class="form-label">نهاية العقد</label>
                    <input type="date" class="form-control" wire:model="end_date">
                    @error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- الإجمالي --}}
                <div class="col-md-4">
                    <label class="form-label">الإجمالي <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control" wire:model="amount">
                    @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- شامل الضريبة --}}
                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="incTax" wire:model="include_tax">
                        <label for="incTax" class="form-check-label">شامل الضريبة</label>
                    </div>
                </div>

                {{-- ملف العقد --}}
                <div class="col-md-4">
                    <label class="form-label">ملف العقد</label>
                    <input type="file" class="form-control" wire:model="contract_file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                    @error('contract_file') <small class="text-danger">{{ $message }}</small> @enderror
                    <div wire:loading wire:target="contract_file" class="small text-muted mt-1">جاري رفع الملف…</div>
                </div>

                {{-- الحالة --}}
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

        {{-- البنود --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">بنود العقد</span>
                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addItem">
                    <i class="mdi mdi-plus-circle-outline me-1"></i> إضافة بند
                </button>
            </div>
            <div class="card-body">
                @forelse($items as $idx => $it)
                    <div class="row g-2 align-items-start mb-2" wire:key="item-{{ $idx }}">
                        <div class="col-md-3">
                            <input class="form-control" placeholder="عنوان" wire:model.defer="items.{{ $idx }}.title">
                            @error("items.$idx.title") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-8">
                            <textarea class="form-control" rows="2" placeholder="النص" wire:model.defer="items.{{ $idx }}.body"></textarea>
                            @error("items.$idx.body") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button class="btn btn-outline-danger" type="button" wire:click="removeItem({{ $idx }})">حذف</button>
                        </div>
                    </div>
                @empty
                    <div class="text-muted">لا توجد بنود بعد.</div>
                @endforelse
            </div>
        </div>

        {{-- الدفعات --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">دفعات العقد</span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addPayment('milestone')">
                        <i class="mdi mdi-plus-circle-outline me-1"></i> إضافة دفعة مرحلية
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="addPayment('monthly')">
                        <i class="mdi mdi-plus-circle-outline me-1"></i> إضافة دفعة شهرية
                    </button>
                </div>
            </div>
            <div class="card-body">
                @forelse($payments as $pidx => $p)
                    <div class="row g-2 payment-row border rounded p-2 mb-3" wire:key="pay-{{ $pidx }}">
                        <div class="col-md-2">
                            <label class="form-label small">النوع</label>
                            <select class="form-select" wire:model.live="payments.{{ $pidx }}.payment_type">
                                <option value="milestone">مرحلية</option>
                                <option value="monthly">شهرية</option>
                            </select>
                            @error("payments.$pidx.payment_type") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small">العنوان</label>
                            <input class="form-control" wire:model.defer="payments.{{ $pidx }}.title" placeholder="عنوان">
                            @error("payments.$pidx.title") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

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

                        <div class="col-md-2">
                            <label class="form-label small">الشرط</label>
                            <select class="form-select" wire:model.defer="payments.{{ $pidx }}.condition">
                                <option value="date">تاريخ</option>
                                <option value="stage">مرحلة</option>
                            </select>
                            @error("payments.$pidx.condition") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small">تاريخ الاستحقاق</label>
                            <input type="date" class="form-control" wire:model.defer="payments.{{ $pidx }}.due_date">
                            @error("payments.$pidx.due_date") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small">المبلغ</label>
                            <input type="number" step="0.01" class="form-control" wire:model.defer="payments.{{ $pidx }}.amount" placeholder="المبلغ">
                            @error("payments.$pidx.amount") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-2">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" wire:model.defer="payments.{{ $pidx }}.include_tax" id="p{{ $pidx }}inc">
                                <label for="p{{ $pidx }}inc" class="form-check-label">شامل</label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" wire:model.defer="payments.{{ $pidx }}.is_paid" id="p{{ $pidx }}paid">
                                <label for="p{{ $pidx }}paid" class="form-check-label">مدفوعة</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small">ملاحظات</label>
                            <input class="form-control" wire:model.defer="payments.{{ $pidx }}.notes" placeholder="ملاحظات">
                            @error("payments.$pidx.notes") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

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

        <div class="text-end">
            <button class="btn btn-success px-4" wire:loading.attr="disabled">
                <span wire:loading.remove>حفظ العقد</span>
                <span wire:loading>جاري الحفظ…</span>
            </button>
        </div>
    </form>
</div>
