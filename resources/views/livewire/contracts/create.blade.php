<div class="container" dir="rtl">
    {{-- عنوان الصفحة مع أيقونة --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-primary">
            <i class="mdi mdi-file-document-edit-outline me-2"></i>
            {{ $contract ? 'تعديل عقد' : 'إنشاء عقد جديد' }}
        </h3>
        <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="mdi mdi-arrow-left-circle-outline me-1"></i> رجوع
        </a>
    </div>

    {{-- رسائل التنبيه --}}
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center mb-4">
            <i class="mdi mdi-check-circle-outline me-2 fs-4"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center mb-4">
            <i class="mdi mdi-alert-circle-outline me-2 fs-4"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    {{-- نموذج العقد --}}
    <form wire:submit.prevent="save" id="contractForm">
        @csrf

        {{-- بيانات العقد الأساسية --}}
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light-primary d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="mdi mdi-card-account-details-outline text-primary me-2"></i>
                    البيانات الأساسية
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    {{-- العميل --}}
                    <div class="col-md-4">
                        <label class="form-label d-flex align-items-center">
                            <i class="mdi mdi-account-outline me-2 text-muted"></i>
                            العميل <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="mdi mdi-account-search-outline"></i>
                            </span>
                            <select class="form-select" wire:model.live="client_id">
                                <option value="">اختر عميلاً</option>
                                @foreach($clients as $cl)
                                    <option value="{{ $cl['id'] }}">{{ $cl['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('client_id') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        <div wire:loading wire:target="client_id" class="small text-muted mt-1">
                            <i class="mdi mdi-loading mdi-spin me-1"></i> جاري تحميل المشاريع...
                        </div>
                    </div>

                    {{-- المشروع --}}
                    <div class="col-md-4">
                        <label class="form-label d-flex align-items-center">
                            <i class="mdi mdi-home-city-outline me-2 text-muted"></i>
                            المشروع
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="mdi mdi-home-search-outline"></i>
                            </span>
                            <select class="form-select" wire:model.live="project_id" @disabled(empty($projects))>
                                <option value="">—</option>
                                @foreach($projects as $pr)
                                    <option value="{{ $pr['id'] }}">{{ $pr['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('project_id') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        <div wire:loading wire:target="project_id" class="small text-muted mt-1">
                            <i class="mdi mdi-loading mdi-spin me-1"></i> جاري تحميل العروض...
                        </div>
                    </div>

                    {{-- العرض --}}
                    <div class="col-md-4">
                        <label class="form-label d-flex align-items-center">
                            <i class="mdi mdi-file-send-outline me-2 text-muted"></i>
                            العرض
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="mdi mdi-file-search-outline"></i>
                            </span>
                            <select class="form-select" wire:model.live="offer_id" @disabled(empty($offers))>
                                <option value="">—</option>
                                @foreach($offers as $of)
                                    <option value="{{ $of['id'] }}">
                                        عرض #{{ $of['id'] }}@if(!empty($of['start_date'])) — {{ $of['start_date'] }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('offer_id') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    {{-- نوع العقد --}}
                    <div class="col-md-4">
                        <label class="form-label d-flex align-items-center">
                            <i class="mdi mdi-form-select me-2 text-muted"></i>
                            نوع العقد <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="mdi mdi-form-dropdown"></i>
                            </span>
                            <select class="form-select" wire:model="type">
                                @foreach(\App\Models\Contract::TYPES as $k=>$v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('type') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    {{-- تواريخ العقد --}}
                    <div class="col-md-4">
                        <label class="form-label d-flex align-items-center">
                            <i class="mdi mdi-calendar-start me-2 text-muted"></i>
                            بداية العقد
                        </label>
                        <input type="date" class="form-control" wire:model="start_date">
                        @error('start_date') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label d-flex align-items-center">
                            <i class="mdi mdi-calendar-end me-2 text-muted"></i>
                            نهاية العقد
                        </label>
                        <input type="date" class="form-control" wire:model="end_date">
                        @error('end_date') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    {{-- القيمة المالية --}}
                    <div class="col-md-4">
                        <label class="form-label d-flex align-items-center">
                            <i class="mdi mdi-cash-multiple me-2 text-muted"></i>
                            الإجمالي <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="mdi mdi-currency-usd"></i>
                            </span>
                            <input type="number" step="0.01" class="form-control" wire:model="amount" placeholder="0.00">
                            <span class="input-group-text bg-white">ج.م</span>
                        </div>
                        @error('amount') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    {{-- خيارات الضريبة والحالة --}}
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" id="incTax" wire:model="include_tax">
                            <label for="incTax" class="form-check-label">
                                <i class="mdi mdi-receipt-text-outline me-1"></i> شامل الضريبة
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label d-flex align-items-center">
                            <i class="mdi mdi-alert-circle-outline me-2 text-muted"></i>
                            الحالة
                        </label>
                        <select class="form-select" wire:model="status">
                            <option value="draft">مسودة</option>
                            <option value="active">ساري</option>
                            <option value="suspended">موقوف</option>
                            <option value="completed">مكتمل</option>
                            <option value="cancelled">ملغي</option>
                        </select>
                        @error('status') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    {{-- ملف العقد --}}
                    <div class="col-md-12">
                        <label class="form-label d-flex align-items-center">
                            <i class="mdi mdi-file-upload-outline me-2 text-muted"></i>
                            ملف العقد
                        </label>
                        <div class="input-group">
                            <input type="file" class="form-control" wire:model="contract_file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                            <button class="btn btn-outline-secondary" type="button" wire:target="contract_file" wire:loading.attr="disabled">
                                <i class="mdi mdi-cloud-upload-outline"></i>
                            </button>
                        </div>
                        @error('contract_file') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        <div wire:loading wire:target="contract_file" class="small text-muted mt-1">
                            <i class="mdi mdi-loading mdi-spin me-1"></i> جاري رفع الملف...
                        </div>
                        @if($contract && $contract->contract_file)
                            <div class="small text-success mt-1">
                                <i class="mdi mdi-check-circle-outline me-1"></i> يوجد ملف مرفق حالياً
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- بنود العقد --}}
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light-primary d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="mdi mdi-format-list-checks text-primary me-2"></i>
                    بنود العقد
                </h5>
                <button type="button" class="btn btn-sm btn-primary rounded-pill" wire:click="addItem">
                    <i class="mdi mdi-plus-circle-outline me-1"></i> إضافة بند
                </button>
            </div>
            <div class="card-body">
                @forelse($items as $idx => $it)
                    <div class="row g-2 align-items-start mb-3" wire:key="item-{{ $idx }}">
                        <div class="col-md-1">
                            <label class="form-label small">الترتيب</label>
                            <input type="number" class="form-control" wire:model.defer="items.{{ $idx }}.sort_order" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">العنوان</label>
                            <input class="form-control" placeholder="عنوان البند" wire:model.defer="items.{{ $idx }}.title">
                            @error("items.$idx.title") <small class="text-danger d-block">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-7">
                            <label class="form-label small">النص</label>
                            <textarea class="form-control" rows="2" placeholder="نص البند" wire:model.defer="items.{{ $idx }}.body"></textarea>
                            @error("items.$idx.body") <small class="text-danger d-block">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button class="btn btn-outline-danger btn-sm w-100" type="button" wire:click="removeItem({{ $idx }})">
                                <i class="mdi mdi-delete-outline"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-light text-center py-4">
                        <i class="mdi mdi-information-outline me-2 fs-4"></i>
                        لا توجد بنود مضافة
                    </div>
                @endforelse
            </div>
        </div>

        {{-- دفعات العقد --}}
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light-primary d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="mdi mdi-currency-usd text-primary me-2"></i>
                    دفعات العقد
                </h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-primary rounded-pill" wire:click="addPayment('milestone')">
                        <i class="mdi mdi-flag-outline me-1"></i> دفعة مرحلية
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" wire:click="addPayment('monthly')">
                        <i class="mdi mdi-calendar-month-outline me-1"></i> دفعة شهرية
                    </button>
                </div>
            </div>
            <div class="card-body">
                @forelse($payments as $pidx => $p)
                    <div class="border rounded p-3 mb-3" wire:key="pay-{{ $pidx }}">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label small">نوع الدفعة</label>
                                <select class="form-select" wire:model.live="payments.{{ $pidx }}.payment_type">
                                    <option value="milestone">مرحلية</option>
                                    <option value="monthly">شهرية</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small">العنوان</label>
                                <input class="form-control" wire:model.defer="payments.{{ $pidx }}.title" placeholder="وصف الدفعة">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small">المرحلة</label>
                                <select class="form-select" wire:model.defer="payments.{{ $pidx }}.stage" @disabled($payments[$pidx]['payment_type'] !== 'milestone')>
                                    <option value="">—</option>
                                    @foreach(\App\Models\ContractPayment::STAGES as $k=>$v)
                                        <option value="{{ $k }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small">شرط الدفع</label>
                                <select class="form-select" wire:model.defer="payments.{{ $pidx }}.condition">
                                    <option value="date">تاريخ محدد</option>
                                    <option value="stage">إنجاز مرحلة</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small">تاريخ الاستحقاق</label>
                                <input type="date" class="form-control" wire:model.defer="payments.{{ $pidx }}.due_date">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small">المبلغ</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" wire:model.defer="payments.{{ $pidx }}.amount" placeholder="0.00">
                                    <span class="input-group-text">ج.م</span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small">ملاحظات</label>
                                <input class="form-control" wire:model.defer="payments.{{ $pidx }}.notes" placeholder="ملاحظات إضافية">
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <div class="form-check form-switch mt-3">
                                    <input type="checkbox" class="form-check-input" wire:model.defer="payments.{{ $pidx }}.include_tax" id="p{{ $pidx }}inc">
                                    <label for="p{{ $pidx }}inc" class="form-check-label">شامل الضريبة</label>
                                </div>
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <div class="form-check form-switch mt-3">
                                    <input type="checkbox" class="form-check-input" wire:model.defer="payments.{{ $pidx }}.is_paid" id="p{{ $pidx }}paid">
                                    <label for="p{{ $pidx }}paid" class="form-check-label">تم الدفع</label>
                                </div>
                            </div>

                            <div class="col-12 text-end">
                                <button class="btn btn-outline-danger btn-sm rounded-pill" type="button" wire:click="removePayment({{ $pidx }})">
                                    <i class="mdi mdi-delete-outline me-1"></i> حذف الدفعة
                                </button>
                            </div>
                        </div>
                        @error("payments.$pidx") <small class="text-danger d-block mt-2">{{ $message }}</small> @enderror
                    </div>
                @empty
                    <div class="alert alert-light text-center py-4">
                        <i class="mdi mdi-information-outline me-2 fs-4"></i>
                        لا توجد دفعات مضافة
                    </div>
                @endforelse
            </div>
        </div>

        {{-- زر الحفظ --}}
        <div class="d-flex justify-content-between border-top pt-4">
            <button type="button" class="btn btn-outline-secondary rounded-pill" wire:click="$set('status', 'draft')" wire:loading.attr="disabled">
                <i class="mdi mdi-content-save-outline me-1"></i> حفظ كمسودة
            </button>
            <button class="btn btn-primary px-4 rounded-pill" wire:loading.attr="disabled">
                <span wire:loading.remove>
                    <i class="mdi mdi-content-save-check-outline me-1"></i> حفظ العقد
                </span>
                <span wire:loading>
                    <i class="mdi mdi-loading mdi-spin me-1"></i> جاري الحفظ...
                </span>
            </button>
        </div>
    </form>
</div>