<div class="container" dir="rtl">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">قائمة العقود</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('contracts.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus-circle-outline"></i> عقد جديد 
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- فلاتر سريعة --}}
    <div class="card mb-3">
        <div class="card-body row g-2">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="بحث بالرقم أو اسم العميل"
                       wire:model.debounce.500ms="search">
            </div>
            <div class="col-md-3">
                <select class="form-select" wire:model="type">
                    <option value="">كل أنواع العقود</option>
                    @foreach($types as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" wire:model="status">
                    <option value="">كل الحالات</option>
                    <option value="draft">مسودة</option>
                    <option value="active">ساري</option>
                    <option value="suspended">موقوف</option>
                    <option value="completed">مكتمل</option>
                    <option value="cancelled">ملغي</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" wire:model="perPage">
                    <option value="10">10 / صفحة</option>
                    <option value="20">20 / صفحة</option>
                    <option value="50">50 / صفحة</option>
                </select>
            </div>
        </div>
    </div>

    {{-- قائمة العقود --}}
    @forelse($contracts as $c)
        <div class="card mb-4 shadow-sm">
            <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                <span>
                    عقد #{{ $c->id }} — {{ $c->client?->name ?? 'بدون عميل' }}
                </span>
                <div class="d-flex gap-2">
                    <a href="{{ route('contracts.show', $c) }}" class="btn btn-sm btn-outline-primary">
                        عرض 
                    </a>
                    <a href="{{ route('contracts.edit', $c) }}" class="btn btn-sm btn-outline-warning">
                        تعديل 
                    </a>
                    <button class="btn btn-sm btn-outline-danger"
                            onclick="if(confirm('حذف العقد؟')) @this.delete({{ $c->id }})">
                        حذف
                    </button>
                </div>
            </div>

            <div class="card-body">
                {{-- جدول بيانات العقد --}}
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-sm">
                        <tbody>
                        <tr>
                            <th style="width:200px">العميل</th><td>{{ $c->client?->name }}</td>
                            <th>نوع العقد</th><td>{{ \App\Models\Contract::TYPES[$c->type] ?? $c->type }}</td>
                        </tr>
                        <tr>
                            <th>المشروع</th><td>{{ $c->project?->name ?? '—' }}</td>
                            <th>العرض</th><td>{{ $c->offer ? ('#'.$c->offer->id) : '—' }}</td>
                        </tr>
                        <tr>
                            <th>الفترة</th>
                            <td>{{ optional($c->start_date)->format('Y-m-d') }} — {{ optional($c->end_date)->format('Y-m-d') }}</td>
                            <th>الحالة</th><td>{{ $c->status ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>الإجمالي</th>
                            <td>{{ number_format($c->amount,2) }} {{ $c->include_tax ? '(شامل الضريبة)' : '' }}</td>
                            
                         
                        </tr>
                        </tbody>
                    </table>
                </div>

                {{-- بنود العقد --}}
                <h6 class="fw-bold">بنود العقد</h6>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-sm align-middle">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الترتيب</th>
                            <th>العنوان</th>
                            <th>النص</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($c->items as $idx => $it)
                            <tr>
                                <td>{{ $idx+1 }}</td>
                                <td>{{ $it->sort_order }}</td>
                                <td>{{ $it->title }}</td>
                                <td>{{ $it->body }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">لا توجد بنود</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- دفعات العقد --}}
                <h6 class="fw-bold">دفعات العقد</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>النوع</th>
                            <th>العنوان</th>
                            <th>المرحلة</th>
                            <th>شهر الفترة</th>
                            <th>شرط التحصيل</th>
                            <th>تاريخ الاستحقاق</th>
                            <th>المبلغ</th>
                            <th>شامل ضريبة</th>
                            <th>مدفوعة؟</th>
                            <th>ملاحظات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($c->payments as $i => $p)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $p->payment_type }}</td>
                                <td>{{ $p->title }}</td>
                                <td>{{ $p->stage ? \App\Models\ContractPayment::STAGES[$p->stage] : '—' }}</td>
                                <td>{{ optional($p->period_month)->format('Y-m') }}</td>
                                <td>{{ $p->condition }}</td>
                                <td>{{ optional($p->due_date)->format('Y-m-d') }}</td>
                                <td>{{ number_format($p->amount,2) }}</td>
                                <td>{{ $p->include_tax ? '✓' : '✗' }}</td>
                                <td>{{ $p->is_paid ? '✓' : '✗' }}</td>
                                <td>{{ $p->notes }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="11" class="text-center text-muted">لا توجد دفعات</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    @empty
        <div class="alert alert-info">لا توجد عقود مطابقة.</div>
    @endforelse

    <div class="d-flex justify-content-center">
        {{ $contracts->links() }}
    </div>
</div>


