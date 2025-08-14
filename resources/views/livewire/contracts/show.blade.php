<div class="container" dir="rtl">
    <!-- Header Section - Improved with icons and better spacing -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-primary">
            <i class="mdi mdi-file-document-outline me-2"></i>
            عرض العقد #{{ $contract->id }}
            <small class="text-muted fs-6">بتاريخ {{ $contract->created_at->format('Y-m-d') }}</small>
        </h3>
        <div class="d-flex gap-2">
            <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-warning btn-sm rounded-pill">
                <i class="mdi mdi-pencil-outline me-1"></i> تعديل
            </a>
            <a href="{{ route('contracts.index') }}" class="btn btn-secondary btn-sm rounded-pill">
                <i class="mdi mdi-arrow-left-circle-outline me-1"></i> رجوع
            </a>
            <button class="btn btn-info btn-sm rounded-pill" onclick="window.print()">
                <i class="mdi mdi-printer-outline me-1"></i> طباعة
            </button>
        </div>
    </div>

    <!-- Contract Details Card - Enhanced with better layout and icons -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-light-primary d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="mdi mdi-card-account-details-outline text-primary me-2"></i>
                بيانات العقد الأساسية
            </h5>
            <div>
                <span class="badge bg-{{ $contract->status == 'active' ? 'success' : ($contract->status == 'draft' ? 'secondary' : 'warning') }} rounded-pill">
                    <i class="mdi mdi-{{ $contract->status == 'active' ? 'check-circle' : ($contract->status == 'draft' ? 'file-document-edit' : 'alert-circle') }} me-1"></i>
                    {{ $contract->status == 'active' ? 'ساري' : ($contract->status == 'draft' ? 'مسودة' : $contract->status) }}
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Client Info -->
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-light-primary rounded-circle p-2 me-3">
                                <i class="mdi mdi-account-outline text-primary fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted">العميل</small>
                                <div class="fw-bold fs-5">{{ $contract->client?->name ?? '—' }}</div>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-light-primary rounded-circle p-2 me-3">
                                <i class="mdi mdi-phone-outline text-primary fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted">الاتصال</small>
                                <div class="fw-bold">{{ $contract->client?->phone ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contract Info -->
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted"><i class="mdi mdi-form-select me-1"></i> نوع العقد</small>
                                <div class="fw-bold">{{ \App\Models\Contract::TYPES[$contract->type] ?? $contract->type }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted"><i class="mdi mdi-home-city-outline me-1"></i> المشروع</small>
                                <div class="fw-bold">{{ $contract->project?->name ?? '—' }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted"><i class="mdi mdi-file-document-edit-outline me-1"></i> العرض</small>
                                <div class="fw-bold">{{ $contract->offer ? ('#'.$contract->offer->id) : '—' }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted"><i class="mdi mdi-calendar-range me-1"></i> الفترة</small>
                                <div class="fw-bold">
                                    {{ optional($contract->start_date)->format('Y-m-d') }} <i class="mdi mdi-arrow-left-thin mx-1"></i> {{ optional($contract->end_date)->format('Y-m-d') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Info -->
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <small class="text-muted"><i class="mdi mdi-cash-multiple me-1"></i> القيمة الإجمالية</small>
                                <div class="fw-bold fs-5">{{ number_format($contract->amount,2) }} <small class="text-muted">{{ $contract->include_tax ? '(شامل الضريبة)' : '' }}</small></div>
                            </div>
                            <span class="badge bg-light-primary text-primary">
                                {{ $contract->payment_currency }}
                            </span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="text-muted"><i class="mdi mdi-cash-check me-1"></i> المدفوع</small>
                                <div class="fw-bold text-success">{{ number_format($contract->payments()->where('is_paid', true)->sum('amount'), 2) }}</div>
                            </div>
                            <div>
                                <small class="text-muted"><i class="mdi mdi-cash-remove me-1"></i> المتبقي</small>
                                <div class="fw-bold text-danger">{{ number_format($contract->amount - $contract->payments()->where('is_paid', true)->sum('amount'), 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contract File -->
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <div class="d-flex align-items-center h-100">
                            <div class="bg-light-primary rounded-circle p-2 me-3">
                                <i class="mdi mdi-file-download-outline text-primary fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted">ملف العقد</small>
                                <div class="fw-bold">
                                    @if($contract->contract_file)
                                        <a href="{{ route('contracts.download', $contract) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                            <i class="mdi mdi-download me-1"></i> تنزيل الملف
                                        </a>
                                        <small class="text-muted d-block mt-1">{{ $contract->contract_file_size }}</small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contract Items Card - Enhanced with better styling -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-light-primary d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="mdi mdi-format-list-checks text-primary me-2"></i>
                بنود العقد
            </h5>
            <span class="badge bg-primary rounded-pill">
                {{ $contract->items->count() }} بند
            </span>
        </div>
        <div class="card-body">
            @forelse($contract->items as $i=>$it)
                <div class="card mb-3 shadow-none border">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-light-primary text-primary me-3">{{ $it->sort_order }}</span>
                                <h6 class="mb-0 fw-bold">{{ $it->title }}</h6>
                            </div>
                            <small class="text-muted">#{{ $i+1 }}</small>
                        </div>
                        <p class="mb-0 text-muted ps-5">{{ $it->body }}</p>
                    </div>
                </div>
            @empty
                <div class="alert alert-light text-center py-4">
                    <i class="mdi mdi-information-outline me-2 fs-4"></i>
                    لا توجد بنود في هذا العقد
                </div>
            @endforelse
        </div>
    </div>

    <!-- Contract Payments Card - Enhanced with better visualization -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-light-primary d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="mdi mdi-currency-usd text-primary me-2"></i>
                دفعات العقد
            </h5>
            <div>
                <span class="badge bg-success rounded-pill me-2">
                    <i class="mdi mdi-check-all me-1"></i> مدفوعة: {{ $contract->payments()->where('is_paid', true)->count() }}
                </span>
                <span class="badge bg-warning rounded-pill">
                    <i class="mdi mdi-clock-outline me-1"></i> قيد الانتظار: {{ $contract->payments()->where('is_paid', false)->count() }}
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead class="table-light">
                        <tr>
                            <th width="40">#</th>
                            <th><i class="mdi mdi-credit-card-outline me-1"></i> النوع</th>
                            <th><i class="mdi mdi-format-title me-1"></i> العنوان</th>
                            <th><i class="mdi mdi-stairs me-1"></i> المرحلة</th>
                            <th><i class="mdi mdi-calendar-clock-outline me-1"></i> تاريخ الاستحقاق</th>
                            <th><i class="mdi mdi-cash me-1"></i> المبلغ</th>
                            <th><i class="mdi mdi-progress-check me-1"></i> الحالة</th>
                            <th><i class="mdi mdi-cog-outline me-1"></i> إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contract->payments as $i=>$p)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <i class="mdi mdi-{{ $p->payment_type == 'cash' ? 'cash' : 'credit-card-outline' }} me-1"></i>
                                        {{ $p->payment_type }}
                                    </span>
                                </td>
                                <td>{{ $p->title }}</td>
                                <td>
                                    @if($p->stage)
                                        <span class="badge bg-light-primary text-primary">
                                            {{ \App\Models\ContractPayment::STAGES[$p->stage] }}
                                        </span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-calendar-blank-outline me-2 text-muted"></i>
                                        {{ optional($p->due_date)->format('Y-m-d') }}
                                    </div>
                                </td>
                                <td class="fw-bold">{{ number_format($p->amount,2) }}</td>
                                <td>
                                    @if($p->is_paid)
                                        <span class="badge bg-success rounded-pill">
                                            <i class="mdi mdi-check-circle-outline me-1"></i> مدفوعة
                                        </span>
                                    @else
                                        <span class="badge bg-{{ $p->due_date && $p->due_date->isPast() ? 'danger' : 'warning' }} rounded-pill">
                                            <i class="mdi mdi-{{ $p->due_date && $p->due_date->isPast() ? 'alert-circle-outline' : 'clock-outline' }} me-1"></i>
                                            {{ $p->due_date && $p->due_date->isPast() ? 'متأخرة' : 'قيد الانتظار' }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#paymentDetails{{ $p->id }}">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="alert alert-light">
                                        <i class="mdi mdi-information-outline me-2 fs-4"></i>
                                        لا توجد دفعات مسجلة لهذا العقد
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Notes Section -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light-primary d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="mdi mdi-note-text-outline text-primary me-2"></i>
                ملاحظات إضافية
            </h5>
        </div>
        <div class="card-body">
            @if($contract->notes)
                <div class="alert alert-light">
                    {!! nl2br(e($contract->notes)) !!}
                </div>
            @else
                <div class="alert alert-light text-center py-4">
                    <i class="mdi mdi-information-outline me-2 fs-4"></i>
                    لا توجد ملاحظات إضافية
                </div>
            @endif
        </div>
    </div>
</div>