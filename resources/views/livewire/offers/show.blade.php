<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <!-- Card Header -->
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0">
                    <i class="mdi mdi-file-document-edit-outline me-2 text-primary"></i>
                    تفاصيل العرض
                </h3>

            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <div class="row g-4">
                <!-- Basic Info -->
                <div class="col-md-6">
                    <div class="card border-0 bg-light-subtle h-100">
                        <div class="card-body">
                            <h5 class="fw-semibold mb-4">
                                <i class="mdi mdi-information-outline me-2"></i>
                                المعلومات الأساسية
                            </h5>

                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex align-items-center border-0 py-3">
                                    <i class="mdi mdi-account-outline me-3 fs-4 text-muted"></i>
                                    <div>
                                        <small class="text-muted">العميل</small>
                                        <div class="fw-semibold">{{ $offer->client->name ?? '-' }}</div>
                                    </div>
                                </div>

                                <div class="list-group-item d-flex align-items-center border-0 py-3">
                                    <i class="mdi mdi-home-city-outline me-3 fs-4 text-muted"></i>
                                    <div>
                                        <small class="text-muted">المشروع</small>
                                        <div class="fw-semibold">{{ $offer->project->name ?? '-' }}</div>
                                    </div>
                                </div>

                                <div class="list-group-item d-flex align-items-center border-0 py-3">
                                    <i class="mdi mdi-calendar-start me-3 fs-4 text-muted"></i>
                                    <div>
                                        <small class="text-muted">تاريخ البداية</small>
                                        <div class="fw-semibold">{{ $offer->start_date }}</div>
                                    </div>
                                </div>

                                <div class="list-group-item d-flex align-items-center border-0 py-3">
                                    <i class="mdi mdi-calendar-end me-3 fs-4 text-muted"></i>
                                    <div>
                                        <small class="text-muted">تاريخ النهاية</small>
                                        <div class="fw-semibold">{{ $offer->end_date }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Info -->
                <div class="col-md-6">
                    <div class="card border-0 bg-light-subtle h-100">
                        <div class="card-body">
                            <h5 class="fw-semibold mb-4">
                                <i class="mdi mdi-cash-multiple me-2"></i>
                                المعلومات المالية
                            </h5>

                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex align-items-center border-0 py-3">
                                    <i class="mdi mdi-currency-usd me-3 fs-4 text-muted"></i>
                                    <div>
                                        <small class="text-muted">قيمة العرض</small>
                                        <div class="fw-semibold">{{ number_format($offer->amount, 2) }} ج.م</div>
                                    </div>
                                </div>

                                <div class="list-group-item d-flex align-items-center border-0 py-3">
                                    <i class="mdi mdi-receipt-text-check-outline me-3 fs-4 text-muted"></i>
                                    <div>
                                        <small class="text-muted">شامل الضريبة</small>
                                        <div class="fw-semibold">{{ $offer->include_tax ? 'نعم' : 'لا' }}</div>
                                    </div>
                                </div>

                                <div class="list-group-item d-flex align-items-center border-0 py-3">
                                    <i class="mdi mdi-state-machine me-3 fs-4 text-muted"></i>
                                    <div>
                                        <small class="text-muted">حالة العرض</small>
                                        <div class="fw-semibold">
                                            @if ($offer->status == 'active')
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="mdi mdi-check-circle-outline me-1"></i>نشط
                                                </span>
                                            @elseif($offer->status == 'pending')
                                                <span class="badge bg-warning-subtle text-warning">
                                                    <i class="mdi mdi-clock-outline me-1"></i>قيد المراجعة
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">
                                                    <i class="mdi mdi-close-circle-outline me-1"></i>ملغى
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Details Section -->
                <div class="col-12">
                    <div class="card border-0 bg-light-subtle">
                        <div class="card-body">
                            <h5 class="fw-semibold mb-4">
                                <i class="mdi mdi-text-box-search-outline me-2"></i>
                                تفاصيل العرض
                            </h5>
                            <div class="p-3 bg-white rounded-3 border">
                                {!! nl2br(e($offer->details)) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                @if ($offer->description)
                    <div class="col-12">
                        <div class="card border-0 bg-light-subtle">
                            <div class="card-body">
                                <h5 class="fw-semibold mb-4">
                                    <i class="mdi mdi-note-text-outline me-2"></i>
                                    ملاحظات إضافية
                                </h5>
                                <div class="p-3 bg-white rounded-3 border">
                                    {!! nl2br(e($offer->description)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('offers.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="mdi mdi-arrow-left me-1"></i> رجوع للقائمة
                </a>

                <div class="d-flex gap-2">
                    <a href="{{ route('offers.edit', $offer->id) }}" class="btn btn-primary rounded-pill px-4">
                        <i class="mdi mdi-pencil-outline me-1"></i> تعديل العرض
                    </a>
                    <button class="btn btn-danger rounded-pill px-4"
                        onclick="confirm('هل أنت متأكد من حذف هذا العرض؟') || event.stopImmediatePropagation()"
                        wire:click="delete({{ $offer->id }})">
                        <i class="mdi mdi-delete-outline me-1"></i> حذف العرض
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, .05);
    }

    .bg-light-subtle {
        background-color: #f8f9fa;
    }

    .list-group-item {
        border-left: 0;
        border-right: 0;
    }

    .rounded-4 {
        border-radius: 0.75rem;
    }

    .badge {
        padding: 0.35rem 0.65rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }

    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1);
    }

    .bg-warning-subtle {
        background-color: rgba(255, 193, 7, 0.1);
    }

    .bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.1);
    }
</style>
