
<div class="container" dir="rtl">
    <h3 class="mb-3">إنشاء عقد جديد</h3>

    <form wire:submit.prevent="save" action="{{ route('contracts.store') }}" method="POST" enctype="multipart/form-data" id="contractForm">
        @csrf

        {{-- بيانات العقد --}}
        <div class="card mb-4">
            <div class="card-header fw-bold">بيانات العقد</div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">العميل</label>
                    <select name="client_id" class="form-select" required>
                        <option value="">اختر</option>
                        @foreach($clients as $cl)
    <option value="{{ $cl['id'] }}">{{ $cl['name'] }}</option>
@endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">المشروع</label>
                    <select name="project_id" class="form-select">
                        <option value="">—</option>
                        {{-- املأ حسب احتياجك --}}
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">العرض</label>
                    <select name="offer_id" class="form-select">
                        <option value="">—</option>
                        {{-- املأ حسب احتياجك --}}
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">نوع العقد</label>
                    <select name="type" class="form-select" required>
                        @foreach(\App\Models\Contract::TYPES as $k=>$v)
                            <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">بداية العقد</label>
                    <input type="date" name="start_date" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">نهاية العقد</label>
                    <input type="date" name="end_date" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">الإجمالي</label>
                    <input type="number" step="0.01" name="amount" class="form-control" required>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="include_tax" id="incTax">
                        <label for="incTax" class="form-check-label">شامل الضريبة</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">ملف العقد</label>
                    <input type="file" name="contract_file" class="form-control" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                </div>

                <div class="col-md-4">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="draft">مسودة</option>
                        <option value="active" selected>ساري</option>
                        <option value="suspended">موقوف</option>
                        <option value="completed">مكتمل</option>
                        <option value="cancelled">ملغي</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- البنود --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <span class="fw-bold">بنود العقد</span>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem()">إضافة بند</button>
            </div>
            <div class="card-body" id="itemsBox">
                {{-- صف افتراضي --}}
                <div class="row g-2 item-row">
                    <div class="col-md-3"><input class="form-control" name="items[0][title]" placeholder="عنوان" required></div>
                    <div class="col-md-8"><textarea class="form-control" name="items[0][body]" rows="2" placeholder="النص"></textarea></div>
                    <div class="col-md-1 d-flex align-items-center">
                        <button class="btn btn-outline-danger" type="button" onclick="removeRow(this)">حذف</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- الدفعات --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <span class="fw-bold">دفعات العقد</span>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addPayment('milestone')">إضافة دفعة مرحلية</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addPayment('monthly')">إضافة دفعة شهرية</button>
                </div>
            </div>
            <div class="card-body" id="paymentsBox">
                {{-- صف افتراضي للمرحلية --}}
                <div class="row g-2 payment-row">
                    <div class="col-md-2">
                        <select class="form-select" name="payments[0][payment_type]">
                            <option value="milestone">مرحلية</option>
                            <option value="monthly">شهرية</option>
                        </select>
                    </div>
                    <div class="col-md-2"><input class="form-control" name="payments[0][title]" placeholder="عنوان"></div>
                    <div class="col-md-2">
                        <select class="form-select" name="payments[0][stage]">
                            <option value="">—</option>
                            @foreach(\App\Models\ContractPayment::STAGES as $k=>$v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="payments[0][condition]">
                            <option value="date">تاريخ</option>
                            <option value="stage">مرحلة</option>
                        </select>
                    </div>
                    <div class="col-md-2"><input type="date" class="form-control" name="payments[0][due_date]"></div>
                    <div class="col-md-2"><input type="number" step="0.01" class="form-control" name="payments[0][amount]" placeholder="المبلغ"></div>
                    <div class="col-md-2">
                        <div class="form-check mt-1">
                            <input type="checkbox" class="form-check-input" name="payments[0][include_tax]" id="p0inc">
                            <label for="p0inc" class="form-check-label">شامل</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-1">
                            <input type="checkbox" class="form-check-input" name="payments[0][is_paid]" id="p0paid">
                            <label for="p0paid" class="form-check-label">مدفوعة</label>
                        </div>
                    </div>
                    <div class="col-md-6"><input class="form-control" name="payments[0][notes]" placeholder="ملاحظات"></div>
                    <div class="col-12 text-end">
                        <button class="btn btn-outline-danger btn-sm" type="button" onclick="removeRow(this)">حذف الدفعة</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button class="btn btn-success px-4">حفظ العقد</button>
        </div>
    </form>
</div>

{{-- سكربت بسيط لتكرار الصفوف --}}
<script>
let itemIndex = 1;
let payIndex  = 1;

function removeRow(btn){ btn.closest('.row').remove(); }

function addItem(){
    const box = document.getElementById('itemsBox');
    const html = `
    <div class="row g-2 item-row">
        <div class="col-md-3"><input class="form-control" name="items[${itemIndex}][title]" placeholder="عنوان" required></div>
        <div class="col-md-8"><textarea class="form-control" name="items[${itemIndex}][body]" rows="2" placeholder="النص"></textarea></div>
        <div class="col-md-1 d-flex align-items-center">
            <button class="btn btn-outline-danger" type="button" onclick="removeRow(this)">حذف</button>
        </div>
    </div>`;
    box.insertAdjacentHTML('beforeend', html);
    itemIndex++;
}

function addPayment(type){
    const box = document.getElementById('paymentsBox');
    const html = `
    <div class="row g-2 payment-row">
        <div class="col-md-2">
            <select class="form-select" name="payments[${payIndex}][payment_type]">
                <option value="milestone" ${type==='milestone'?'selected':''}>مرحلية</option>
                <option value="monthly" ${type==='monthly'?'selected':''}>شهرية</option>
            </select>
        </div>
        <div class="col-md-2"><input class="form-control" name="payments[${payIndex}][title]" placeholder="عنوان"></div>
        <div class="col-md-2">
            <select class="form-select" name="payments[${payIndex}][stage]">
                <option value="">—</option>
                @foreach(\App\Models\ContractPayment::STAGES as $k=>$v)
                    <option value="{{ $k }}">{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select" name="payments[${payIndex}][condition]">
                <option value="date">تاريخ</option>
                <option value="stage">مرحلة</option>
            </select>
        </div>
        <div class="col-md-2"><input type="date" class="form-control" name="payments[${payIndex}][due_date]"></div>
        <div class="col-md-2"><input type="number" step="0.01" class="form-control" name="payments[${payIndex}][amount]" placeholder="المبلغ"></div>
        <div class="col-md-2">
            <div class="form-check mt-1"><input type="checkbox" class="form-check-input" name="payments[${payIndex}][include_tax]"><label class="form-check-label">شامل</label></div>
        </div>
        <div class="col-md-2">
            <div class="form-check mt-1"><input type="checkbox" class="form-check-input" name="payments[${payIndex}][is_paid]"><label class="form-check-label">مدفوعة</label></div>
        </div>
        <div class="col-md-6"><input class="form-control" name="payments[${payIndex}][notes]" placeholder="ملاحظات"></div>
        <div class="col-12 text-end">
            <button class="btn btn-outline-danger btn-sm" type="button" onclick="removeRow(this)">حذف الدفعة</button>
        </div>
    </div>`;
    box.insertAdjacentHTML('beforeend', html);
    payIndex++;
}
</script>

