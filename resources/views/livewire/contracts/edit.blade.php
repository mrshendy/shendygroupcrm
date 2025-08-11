
<div class="container" dir="rtl">
    <h3 class="mb-3">تعديل العقد #{{ $contract->id }}</h3>

    <form wire:submit.prevent="save" action="{{ route('contracts.update',$contract) }}" method="POST" enctype="multipart/form-data" id="contractForm">
        @csrf @method('PUT')

        {{-- بيانات العقد --}}
        <div class="card mb-4">
            <div class="card-header fw-bold">بيانات العقد</div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">العميل</label>
                    <select name="client_id" class="form-select" required>
                        @foreach($clients as $cl)
                            <option value="{{ $cl['id'] }}" @selected($cl['id']==$contract->client_id)>{{ $cl['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">نوع العقد</label>
                    <select name="type" class="form-select" required>
                        @foreach(\App\Models\Contract::TYPES as $k=>$v)
                            <option value="{{ $k }}" @selected($contract->type==$k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4"><label class="form-label">بداية</label>
                    <input type="date" name="start_date" class="form-control" value="{{ optional($contract->start_date)->format('Y-m-d') }}">
                </div>
                <div class="col-md-4"><label class="form-label">نهاية</label>
                    <input type="date" name="end_date" class="form-control" value="{{ optional($contract->end_date)->format('Y-m-d') }}">
                </div>
                <div class="col-md-4"><label class="form-label">الإجمالي</label>
                    <input type="number" step="0.01" name="amount" class="form-control" value="{{ $contract->amount }}" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="include_tax" id="incTax" {{ $contract->include_tax ? 'checked' : '' }}>
                        <label for="incTax" class="form-check-label">شامل الضريبة</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ملف جديد (اختياري)</label>
                    <input type="file" name="contract_file" class="form-control" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                    @if($contract->contract_file)
                        <small class="text-muted d-block mt-1">الحالي: <a target="_blank" href="{{ asset('storage/'.$contract->contract_file) }}">تحميل</a></small>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        @foreach(['draft'=>'مسودة','active'=>'ساري','suspended'=>'موقوف','completed'=>'مكتمل','cancelled'=>'ملغي'] as $k=>$v)
                            <option value="{{ $k }}" @selected(($contract->status??'active')==$k)>{{ $v }}</option>
                        @endforeach
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
                @php $i=0; @endphp
                @forelse($contract->items as $it)
                    <div class="row g-2 item-row">
                        <div class="col-md-3"><input class="form-control" name="items[{{ $i }}][title]" value="{{ $it->title }}" required></div>
                        <div class="col-md-8"><textarea class="form-control" name="items[{{ $i }}][body]" rows="2">{{ $it->body }}</textarea></div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button class="btn btn-outline-danger" type="button" onclick="removeRow(this)">حذف</button>
                        </div>
                    </div>
                    @php $i++; @endphp
                @empty
                    <div class="row g-2 item-row">
                        <div class="col-md-3"><input class="form-control" name="items[0][title]" placeholder="عنوان" required></div>
                        <div class="col-md-8"><textarea class="form-control" name="items[0][body]" rows="2" placeholder="النص"></textarea></div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button class="btn btn-outline-danger" type="button" onclick="removeRow(this)">حذف</button>
                        </div>
                    </div>
                    @php $i=1; @endphp
                @endforelse
            </div>
        </div>

        {{-- الدفعات --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <span class="fw-bold">دفعات العقد</span>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addPayment('milestone')">دفعة مرحلية</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addPayment('monthly')">دفعة شهرية</button>
                </div>
            </div>
            <div class="card-body" id="paymentsBox">
                @php $p=0; @endphp
                @forelse($contract->payments as $pay)
                    <div class="row g-2 payment-row">
                        <div class="col-md-2">
                            <select class="form-select" name="payments[{{ $p }}][payment_type]">
                                <option value="milestone" @selected($pay->payment_type=='milestone')>مرحلية</option>
                                <option value="monthly" @selected($pay->payment_type=='monthly')>شهرية</option>
                            </select>
                        </div>
                        <div class="col-md-2"><input class="form-control" name="payments[{{ $p }}][title]" value="{{ $pay->title }}"></div>
                        <div class="col-md-2">
                            <select class="form-select" name="payments[{{ $p }}][stage]">
                                <option value="">—</option>
                                @foreach(\App\Models\ContractPayment::STAGES as $k=>$v)
                                    <option value="{{ $k }}" @selected($pay->stage==$k)>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="payments[{{ $p }}][condition]">
                                <option value="date" @selected($pay->condition=='date')>تاريخ</option>
                                <option value="stage" @selected($pay->condition=='stage')>مرحلة</option>
                            </select>
                        </div>
                        <div class="col-md-2"><input type="date" class="form-control" name="payments[{{ $p }}][due_date]" value="{{ optional($pay->due_date)->format('Y-m-d') }}"></div>
                        <div class="col-md-2"><input type="number" step="0.01" class="form-control" name="payments[{{ $p }}][amount]" value="{{ $pay->amount }}"></div>
                        <div class="col-md-2">
                            <div class="form-check mt-1"><input type="checkbox" class="form-check-input" name="payments[{{ $p }}][include_tax]" {{ $pay->include_tax?'checked':'' }}><label class="form-check-label">شامل</label></div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check mt-1"><input type="checkbox" class="form-check-input" name="payments[{{ $p }}][is_paid]" {{ $pay->is_paid?'checked':'' }}><label class="form-check-label">مدفوعة</label></div>
                        </div>
                        <div class="col-md-6"><input class="form-control" name="payments[{{ $p }}][notes]" value="{{ $pay->notes }}"></div>
                        <div class="col-12 text-end">
                            <button class="btn btn-outline-danger btn-sm" type="button" onclick="removeRow(this)">حذف الدفعة</button>
                        </div>
                    </div>
                    @php $p++; @endphp
                @empty
                    {{-- صف واحد افتراضي --}}
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
                        <div class="col-12 text-end mt-2">
                            <button class="btn btn-outline-danger btn-sm" type="button" onclick="removeRow(this)">حذف الدفعة</button>
                        </div>
                    </div>
                    @php $p=1; @endphp
                @endforelse
            </div>
        </div>

        <div class="text-end">
            <button class="btn btn-warning px-4">تعديل العقد</button>
        </div>
    </form>
</div>

<script>
let itemIndex = {{ $i ?? 1 }};
let payIndex  = {{ $p ?? 1 }};
function removeRow(btn){ btn.closest('.row').remove(); }
function addItem(){
    const box = document.getElementById('itemsBox');
    box.insertAdjacentHTML('beforeend', `
    <div class="row g-2 item-row">
        <div class="col-md-3"><input class="form-control" name="items[${itemIndex}][title]" placeholder="عنوان" required></div>
        <div class="col-md-8"><textarea class="form-control" name="items[${itemIndex}][body]" rows="2" placeholder="النص"></textarea></div>
        <div class="col-md-1 d-flex align-items-center"><button class="btn btn-outline-danger" type="button" onclick="removeRow(this)">حذف</button></div>
    </div>`);
    itemIndex++;
}
function addPayment(type){
    const box = document.getElementById('paymentsBox');
    box.insertAdjacentHTML('beforeend', `
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
        <div class="col-12 text-end mt-2"><button class="btn btn-outline-danger btn-sm" type="button" onclick="removeRow(this)">حذف الدفعة</button></div>
    </div>`);
    payIndex++;
}
</script>

