<div class="container" dir="rtl">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">
            <i class="mdi mdi-file-document-multiple-outline me-2"></i>
            قائمة العقود
        </h3>
        <div class="d-flex gap-2">
            <a href="{{ route('contracts.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus-circle-outline me-1"></i> 
                عقد جديد
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center">
            <i class="mdi mdi-check-circle-outline me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters Card -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="mdi mdi-filter-outline me-2"></i>
                فلاتر البحث
            </h5>
        </div>
        <div class="card-body row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="mdi mdi-magnify"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="بحث بالرقم أو اسم العميل"
                           wire:model.debounce.500ms="search">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="mdi mdi-form-select"></i>
                    </span>
                    <select class="form-select" wire:model="type">
                        <option value="">كل أنواع العقود</option>
                        @foreach($types as $k => $v)
                            <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="mdi mdi-list-status"></i>
                    </span>
                    <select class="form-select" wire:model="status">
                        <option value="">كل الحالات</option>
                        <option value="draft">مسودة</option>
                        <option value="active">ساري</option>
                        <option value="suspended">موقوف</option>
                        <option value="completed">مكتمل</option>
                        <option value="cancelled">ملغي</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="mdi mdi-format-list-numbered"></i>
                    </span>
                    <select class="form-select" wire:model="perPage">
                        <option value="10">10 / صفحة</option>
                        <option value="20">20 / صفحة</option>
                        <option value="50">50 / صفحة</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Contracts List -->
    @forelse($contracts as $c)
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="mdi mdi-file-document-outline me-2 fs-4 text-primary"></i>
                    <div>
                        <h5 class="mb-0 fw-bold">عقد #{{ $c->id }}</h5>
                        <small class="text-muted">
                            <i class="mdi mdi-account-outline me-1"></i>
                            {{ $c->client?->name ?? 'بدون عميل' }}
                        </small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('contracts.show', $c) }}" class="btn btn-sm btn-outline-primary">
                        <i class="mdi mdi-eye-outline me-1"></i> عرض
                    </a>
                    <a href="{{ route('contracts.edit', $c) }}" class="btn btn-sm btn-outline-warning">
                        <i class="mdi mdi-pencil-outline me-1"></i> تعديل
                    </a>
                    <button class="btn btn-sm btn-outline-danger"
                            onclick="if(confirm('حذف العقد؟')) @this.delete({{ $c->id }})">
                        <i class="mdi mdi-delete-outline me-1"></i> حذف
                    </button>
                </div>
            </div>

            <div class="card-body">
                <!-- Contract Summary -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="mdi mdi-calendar-check-outline me-2 text-muted"></i>
                            <div>
                                <small class="text-muted">تاريخ البدء</small>
                                <div>{{ optional($c->start_date)->format('Y-m-d') ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="mdi mdi-calendar-remove-outline me-2 text-muted"></i>
                            <div>
                                <small class="text-muted">تاريخ الانتهاء</small>
                                <div>{{ optional($c->end_date)->format('Y-m-d') ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="mdi mdi-cash-multiple me-2 text-muted"></i>
                            <div>
                                <small class="text-muted">القيمة الإجمالية</small>
                                <div>{{ number_format($c->amount,2) }} {{ $c->include_tax ? '(شامل الضريبة)' : '' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="mdi mdi-progress-check me-2 text-muted"></i>
                            <div>
                                <small class="text-muted">حالة العقد</small>
                                <div>
                                    @switch($c->status)
                                        @case('draft')
                                            <span class="badge bg-secondary">مسودة</span>
                                            @break
                                        @case('active')
                                            <span class="badge bg-success">ساري</span>
                                            @break
                                        @case('suspended')
                                            <span class="badge bg-warning text-dark">موقوف</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-primary">مكتمل</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">ملغي</span>
                                            @break
                                        @default
                                            <span class="badge bg-light text-dark">—</span>
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contract Details Tabs -->
                <ul class="nav nav-tabs mb-4" id="contractTabs{{ $c->id }}" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="details-tab-{{ $c->id }}" data-bs-toggle="tab" 
                                data-bs-target="#details-{{ $c->id }}" type="button" role="tab">
                            <i class="mdi mdi-information-outline me-1"></i> تفاصيل العقد
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="items-tab-{{ $c->id }}" data-bs-toggle="tab" 
                                data-bs-target="#items-{{ $c->id }}" type="button" role="tab">
                            <i class="mdi mdi-format-list-bulleted me-1"></i> بنود العقد
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="payments-tab-{{ $c->id }}" data-bs-toggle="tab" 
                                data-bs-target="#payments-{{ $c->id }}" type="button" role="tab">
                            <i class="mdi mdi-currency-usd me-1"></i> دفعات العقد
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="contractTabsContent{{ $c->id }}">
                    <!-- Details Tab -->
                    <div class="tab-pane fade show active" id="details-{{ $c->id }}" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <tbody>
                                    <tr>
                                        <th style="width:200px"><i class="mdi mdi-account-outline me-1"></i> العميل</th>
                                        <td>{{ $c->client?->name ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="mdi mdi-form-select me-1"></i> نوع العقد</th>
                                        <td>{{ \App\Models\Contract::TYPES[$c->type] ?? $c->type }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="mdi mdi-home-city me-1"></i> المشروع</th>
                                        <td>{{ $c->project?->name ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="mdi mdi-file-document-edit-outline me-1"></i> العرض</th>
                                        <td>{{ $c->offer ? ('#'.$c->offer->id) : '—' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Items Tab -->
                    <div class="tab-pane fade" id="items-{{ $c->id }}" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th width="80"><i class="mdi mdi-sort me-1"></i> الترتيب</th>
                                        <th><i class="mdi mdi-format-title me-1"></i> العنوان</th>
                                        <th><i class="mdi mdi-text me-1"></i> النص</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($c->items as $idx => $it)
                                        <tr>
                                            <td>{{ $idx+1 }}</td>
                                            <td>{{ $it->sort_order }}</td>
                                            <td>{{ $it->title }}</td>
                                            <td>{{ Str::limit($it->body, 100) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">
                                                <i class="mdi mdi-information-outline me-1"></i>
                                                لا توجد بنود
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Payments Tab -->
                    <div class="tab-pane fade" id="payments-{{ $c->id }}" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th><i class="mdi mdi-currency-usd me-1"></i> النوع</th>
                                        <th><i class="mdi mdi-format-title me-1"></i> العنوان</th>
                                        <th><i class="mdi mdi-progress-wrench me-1"></i> المرحلة</th>
                                        <th><i class="mdi mdi-calendar-month me-1"></i> شهر الفترة</th>
                                        <th><i class="mdi mdi-alert-circle-outline me-1"></i> شرط التحصيل</th>
                                        <th><i class="mdi mdi-calendar-clock me-1"></i> تاريخ الاستحقاق</th>
                                        <th><i class="mdi mdi-cash me-1"></i> المبلغ</th>
                                        <th><i class="mdi mdi-checkbox-marked me-1"></i> مدفوعة؟</th>
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
                                            <td>{{ Str::limit($p->condition, 20) }}</td>
                                            <td>{{ optional($p->due_date)->format('Y-m-d') }}</td>
                                            <td>{{ number_format($p->amount,2) }}</td>
                                            <td>
                                                @if($p->is_paid)
                                                    <span class="badge bg-success"><i class="mdi mdi-check"></i></span>
                                                @else
                                                    <span class="badge bg-danger"><i class="mdi mdi-close"></i></span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-3">
                                                <i class="mdi mdi-information-outline me-1"></i>
                                                لا توجد دفعات
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="mdi mdi-file-document-remove-outline fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">لا توجد عقود مطابقة</h5>
                <p class="text-muted">لا يوجد عقود مطابقة لمعايير البحث المحددة</p>
                <a href="{{ route('contracts.create') }}" class="btn btn-primary mt-2">
                    <i class="mdi mdi-plus-circle-outline me-1"></i> إنشاء عقد جديد
                </a>
            </div>
        </div>
    @endforelse

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $contracts->links() }}
    </div>
</div>