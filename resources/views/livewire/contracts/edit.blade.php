<div class="container" dir="rtl">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">
            <i class="mdi mdi-file-document-edit-outline text-primary me-2"></i>
            تعديل العقد #{{ $contract->id }}
        </h3>
        <div class="d-flex gap-2">
            <a href="{{ route('contracts.show', $contract) }}" class="btn btn-secondary">
                <i class="mdi mdi-eye-outline me-1"></i> معاينة
            </a>
            <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-right-circle-outline me-1"></i> رجوع
            </a>
        </div>
    </div>

    <form wire:submit.prevent="save" action="{{ route('contracts.update',$contract) }}" method="POST" enctype="multipart/form-data" id="contractForm">
        @csrf @method('PUT')

        <!-- Contract Details Card -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="mdi mdi-information-outline me-2"></i>
                    بيانات العقد
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Client Selection -->
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="mdi mdi-account-outline me-1"></i>
                            العميل
                        </label>
                        <select name="client_id" class="form-select" required>
                            @foreach($clients as $cl)
                                <option value="{{ $cl['id'] }}" @selected($cl['id']==$contract->client_id)>{{ $cl['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Contract Type -->
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="mdi mdi-form-select me-1"></i>
                            نوع العقد
                        </label>
                        <select name="type" class="form-select" required>
                            @foreach(\App\Models\Contract::TYPES as $k=>$v)
                                <option value="{{ $k }}" @selected($contract->type==$k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dates -->
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="mdi mdi-calendar-start me-1"></i>
                            تاريخ البدء
                        </label>
                        <input type="date" name="start_date" class="form-control" value="{{ optional($contract->start_date)->format('Y-m-d') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="mdi mdi-calendar-end me-1"></i>
                            تاريخ الانتهاء
                        </label>
                        <input type="date" name="end_date" class="form-control" value="{{ optional($contract->end_date)->format('Y-m-d') }}">
                    </div>

                    <!-- Amount -->
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="mdi mdi-cash-multiple me-1"></i>
                            القيمة الإجمالية
                        </label>
                        <input type="number" step="0.01" name="amount" class="form-control" value="{{ $contract->amount }}" required>
                    </div>

                    <!-- Tax & Status -->
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" name="include_tax" id="incTax" {{ $contract->include_tax ? 'checked' : '' }}>
                            <label for="incTax" class="form-check-label">
                                <i class="mdi mdi-percent me-1"></i>
                                شامل الضريبة
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="mdi mdi-file-document-outline me-1"></i>
                            ملف العقد
                        </label>
                        <input type="file" name="contract_file" class="form-control" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                        @if($contract->contract_file)
                            <small class="text-muted d-block mt-2">
                                <i class="mdi mdi-file-document me-1"></i>
                                الملف الحالي: <a target="_blank" href="{{ asset('storage/'.$contract->contract_file) }}" class="text-primary">تحميل</a>
                            </small>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="mdi mdi-progress-check me-1"></i>
                            الحالة
                        </label>
                        <select name="status" class="form-select">
                            @foreach(['draft'=>'مسودة','active'=>'ساري','suspended'=>'موقوف','completed'=>'مكتمل','cancelled'=>'ملغي'] as $k=>$v)
                                <option value="{{ $k }}" @selected(($contract->status??'active')==$k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contract Items Card -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="mdi mdi-format-list-bulleted me-2"></i>
                    بنود العقد
                </h5>
                <button type="button" class="btn btn-sm btn-primary" onclick="addItem()">
                    <i class="mdi mdi-plus me-1"></i> إضافة بند
                </button>
            </div>
            <div class="card-body" id="itemsBox">
                @php $i=0; @endphp
                @forelse($contract->items as $it)
                    <div class="row g-3 item-row mb-3">
                        <div class="col-md-3">
                            <input class="form-control" name="items[{{ $i }}][title]" value="{{ $it->title }}" placeholder="عنوان البند" required>
                        </div>
                        <div class="col-md-8">
                            <textarea class="form-control" name="items[{{ $i }}][body]" rows="2" placeholder="تفاصيل البند">{{ $it->body }}</textarea>
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button class="btn btn-outline-danger" type="button" onclick="removeRow(this)">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </div>
                    </div>
                    @php $i++; @endphp
                @empty
                    <div class="row g-3 item-row mb-3">
                        <div class="col-md-3">
                            <input class="form-control" name="items[0][title]" placeholder="عنوان البند" required>
                        </div>
                        <div class="col-md-8">
                            <textarea class="form-control" name="items[0][body]" rows="2" placeholder="تفاصيل البند"></textarea>
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button class="btn btn-outline-danger" type="button" onclick="removeRow(this)">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </div>
                    </div>
                    @php $i=1; @endphp
                @endforelse
            </div>
        </div>

        <!-- Contract Payments Card -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="mdi mdi-currency-usd me-2"></i>
                    دفعات العقد
                </h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-primary" onclick="addPayment('milestone')">
                        <i class="mdi mdi-cash-multiple me-1"></i> دفعة مرحلية
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addPayment('monthly')">
                        <i class="mdi mdi-calendar-month me-1"></i> دفعة شهرية
                    </button>
                </div>
            </div>
            <div class="card-body" id="paymentsBox">
                @php $p=0; @endphp
                @forelse($contract->payments as $pay)
                    <div class="payment-card mb-3 p-3 border rounded">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label small">نوع الدفعة</label>
                                <select class="form-select" name="payments[{{ $p }}][payment_type]">
                                    <option value="milestone" @selected($pay->payment_type=='milestone')>مرحلية</option>
                                    <option value="monthly" @selected($pay->payment_type=='monthly')>شهرية</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">العنوان</label>
                                <input class="form-control" name="payments[{{ $p }}][title]" value="{{ $pay->title }}" placeholder="عنوان الدفعة">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">المرحلة</label>
                                <select class="form-select" name="payments[{{ $p }}][stage]">
                                    <option value="">—</option>
                                    @foreach(\App\Models\ContractPayment::STAGES as $k=>$v)
                                        <option value="{{ $k }}" @selected($pay->stage==$k)>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">شرط التحصيل</label>
                                <select class="form-select" name="payments[{{ $p }}][condition]">
                                    <option value="date" @selected($pay->condition=='date')>تاريخ</option>
                                    <option value="stage" @selected($pay->condition=='stage')>مرحلة</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">تاريخ الاستحقاق</label>
                                <input type="date" class="form-control" name="payments[{{ $p }}][due_date]" value="{{ optional($pay->due_date)->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">المبلغ</label>
                                <input type="number" step="0.01" class="form-control" name="payments[{{ $p }}][amount]" value="{{ $pay->amount }}" placeholder="0.00">
                            </div>
                            <div class="col-md-2">
                                <div class="form-check form-switch mt-4">
                                    <input type="checkbox" class="form-check-input" name="payments[{{ $p }}][include_tax]" id="tax_{{ $p }}" {{ $pay->include_tax?'checked':'' }}>
                                    <label for="tax_{{ $p }}" class="form-check-label small">شامل الضريبة</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check form-switch mt-4">
                                    <input type="checkbox" class="form-check-input" name="payments[{{ $p }}][is_paid]" id="paid_{{ $p }}" {{ $pay->is_paid?'checked':'' }}>
                                    <label for="paid_{{ $p }}" class="form-check-label small">مدفوعة</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small">ملاحظات</label>
                                <input class="form-control" name="payments[{{ $p }}][notes]" value="{{ $pay->notes }}" placeholder="ملاحظات إضافية">
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-outline-danger btn-sm" type="button" onclick="removeRow(this)">
                                    <i class="mdi mdi-delete me-1"></i> حذف الدفعة
                                </button>
                            </div>
                        </div>
                    </div>
                    @php $p++; @endphp
                @empty
                    <div class="payment-card mb-3 p-3 border rounded">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label small">نوع الدفعة</label>
                                <select class="form-select" name="payments[0][payment_type]">
                                    <option value="milestone">مرحلية</option>
                                    <option value="monthly">شهرية</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">العنوان</label>
                                <input class="form-control" name="payments[0][title]" placeholder="عنوان الدفعة">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">المرحلة</label>
                                <select class="form-select" name="payments[0][stage]">
                                    <option value="">—</option>
                                    @foreach(\App\Models\ContractPayment::STAGES as $k=>$v)
                                        <option value="{{ $k }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">شرط التحصيل</label>
                                <select class="form-select" name="payments[0][condition]">
                                    <option value="date">تاريخ</option>
                                    <option value="stage">مرحلة</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">تاريخ الاستحقاق</label>
                                <input type="date" class="form-control" name="payments[0][due_date]">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">المبلغ</label>
                                <input type="number" step="0.01" class="form-control" name="payments[0][amount]" placeholder="0.00">
                            </div>
                            <div class="col-12 text-end mt-2">
                                <button class="btn btn-outline-danger btn-sm" type="button" onclick="removeRow(this)">
                                    <i class="mdi mdi-delete me-1"></i> حذف الدفعة
                                </button>
                            </div>
                        </div>
                    </div>
                    @php $p=1; @endphp
                @endforelse
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-end mb-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="mdi mdi-content-save-outline me-1"></i> حفظ التعديلات
            </button>
        </div>
    </form>
</div>

<script>
let itemIndex = {{ $i ?? 1 }};
let payIndex  = {{ $p ?? 1 }};

function removeRow(btn){ 
    btn.closest('.item-row, .payment-card').remove(); 
}

function addItem(){
    const box = document.getElementById('itemsBox');
    box.insertAdjacentHTML('beforeend', `
    <div class="row g-3 item-row mb-3">
        <div class="col-md-3">
            <input class="form-control" name="items[${itemIndex}][title]" placeholder="عنوان البند" required>
        </div>
        <div class="col-md-8">
            <textarea class="form-control" name="items[${itemIndex}][body]" rows="2" placeholder="تفاصيل البند"></textarea>
        </div>
        <div class="col-md-1 d-flex align-items-center">
            <button class="btn btn-outline-danger" type="button" onclick="removeRow(this)">
                <i class="mdi mdi-delete"></i>
            </button>
        </div>
    </div>`);
    itemIndex++;
}

function addPayment(type){
    const box = document.getElementById('paymentsBox');
    box.insertAdjacentHTML('beforeend', `
    <div class="payment-card mb-3 p-3 border rounded">
        <div class="row g-3">
            <div class="col-md-2">
                <label class="form-label small">نوع الدفعة</label>
                <select class="form-select" name="payments[${payIndex}][payment_type]">
                    <option value="milestone" ${type==='milestone'?'selected':''}>مرحلية</option>
                    <option value="monthly" ${type==='monthly'?'selected':''}>شهرية</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">العنوان</label>
                <input class="form-control" name="payments[${payIndex}][title]" placeholder="عنوان الدفعة">
            </div>
            <div class="col-md-2">
                <label class="form-label small">المرحلة</label>
                <select class="form-select" name="payments[${payIndex}][stage]">
                    <option value="">—</option>
                    @foreach(\App\Models\ContractPayment::STAGES as $k=>$v)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">شرط التحصيل</label>
                <select class="form-select" name="payments[${payIndex}][condition]">
                    <option value="date">تاريخ</option>
                    <option value="stage">مرحلة</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">تاريخ الاستحقاق</label>
                <input type="date" class="form-control" name="payments[${payIndex}][due_date]">
            </div>
            <div class="col-md-2">
                <label class="form-label small">المبلغ</label>
                <input type="number" step="0.01" class="form-control" name="payments[${payIndex}][amount]" placeholder="0.00">
            </div>
            <div class="col-12 text-end mt-2">
                <button class="btn btn-outline-danger btn-sm" type="button" onclick="removeRow(this)">
                    <i class="mdi mdi-delete me-1"></i> حذف الدفعة
                </button>
            </div>
        </div>
    </div>`);
    payIndex++;
}
</script>

<style>
.payment-card {
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}
.payment-card:hover {
    background-color: #f1f1f1;
}
.form-label.small {
    font-size: 0.8rem;
    color: #6c757d;
}
</style>
