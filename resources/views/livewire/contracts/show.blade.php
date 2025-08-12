<div class="container" dir="rtl">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">
            <i class="mdi mdi-file-document-outline text-primary me-2"></i>
            عرض العقد #{{ $contract->id }}
        </h3>
        <div class="d-flex gap-2">
            <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-warning">
                <i class="mdi mdi-pencil-outline me-1"></i> تعديل
            </a>
            <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
                <i class="mdi mdi-arrow-right-circle-outline me-1"></i> رجوع
            </a>
        </div>
    </div>

    <!-- Contract Details Card -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="mdi mdi-information-outline me-2"></i>
                بيانات العقد
            </h5>
            <span class="badge bg-{{ $contract->status == 'active' ? 'success' : ($contract->status == 'draft' ? 'secondary' : 'warning') }}">
                {{ $contract->status == 'active' ? 'ساري' : ($contract->status == 'draft' ? 'مسودة' : $contract->status) }}
            </span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-account-outline text-muted me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">العميل</small>
                            <div class="fw-bold">{{ $contract->client?->name ?? '—' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-form-select text-muted me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">نوع العقد</small>
                            <div class="fw-bold">{{ \App\Models\Contract::TYPES[$contract->type] ?? $contract->type }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-home-city-outline text-muted me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">المشروع</small>
                            <div class="fw-bold">{{ $contract->project?->name ?? '—' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-file-document-edit-outline text-muted me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">العرض</small>
                            <div class="fw-bold">{{ $contract->offer ? ('#'.$contract->offer->id) : '—' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-calendar-range text-muted me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">الفترة</small>
                            <div class="fw-bold">
                                {{ optional($contract->start_date)->format('Y-m-d') }} - {{ optional($contract->end_date)->format('Y-m-d') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-cash-multiple text-muted me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">القيمة الإجمالية</small>
                            <div class="fw-bold">
                                {{ number_format($contract->amount,2) }} {{ $contract->include_tax ? '(شامل الضريبة)' : '' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-file-download-outline text-muted me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">ملف العقد</small>
                            <div class="fw-bold">
                                @if($contract->contract_file)
                                    <a href="{{ route('contracts.download', $contract) }}" class="text-primary">
                                        <i class="mdi mdi-download me-1"></i> تنزيل
                                    </a>
                                @else — @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contract Items Card -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="mdi mdi-format-list-bulleted me-2"></i>
                بنود العقد
            </h5>
        </div>
        <div class="card-body">
            @forelse($contract->items as $i=>$it)
                <div class="card mb-2 shadow-none border">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge bg-light text-dark me-2">{{ $it->sort_order }}</span>
                                <strong>{{ $it->title }}</strong>
                            </div>
                            <span class="text-muted">#{{ $i+1 }}</span>
                        </div>
                        <p class="mb-0 mt-2 text-muted">{{ $it->body }}</p>
                    </div>
                </div>
            @empty
                <div class="alert alert-light text-center">
                    <i class="mdi mdi-information-outline me-2"></i>
                    لا توجد بنود
                </div>
            @endforelse
        </div>
    </div>

    <!-- Contract Payments Card -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="mdi mdi-currency-usd me-2"></i>
                دفعات العقد
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead class="table-light">
                        <tr>
                            <th width="40">#</th>
                            <th>النوع</th>
                            <th>العنوان</th>
                            <th>المرحلة</th>
                            <th>تاريخ الاستحقاق</th>
                            <th>المبلغ</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contract->payments as $i=>$p)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $p->payment_type }}</td>
                                <td>{{ $p->title }}</td>
                                <td>{{ $p->stage ? \App\Models\ContractPayment::STAGES[$p->stage] : '—' }}</td>
                                <td>{{ optional($p->due_date)->format('Y-m-d') }}</td>
                                <td>{{ number_format($p->amount,2) }}</td>
                                <td>
                                    @if($p->is_paid)
                                        <span class="badge bg-success">
                                            <i class="mdi mdi-check me-1"></i> مدفوعة
                                        </span>
                                    @else
                                        <span class="badge bg-{{ $p->due_date && $p->due_date->isPast() ? 'danger' : 'warning' }}">
                                            <i class="mdi mdi-{{ $p->due_date && $p->due_date->isPast() ? 'alert' : 'clock' }} me-1"></i>
                                            {{ $p->due_date && $p->due_date->isPast() ? 'متأخرة' : 'قيد الانتظار' }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">
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
