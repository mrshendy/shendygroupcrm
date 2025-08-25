<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <!-- Card Header -->
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0">
                    <i class="mdi mdi-swap-horizontal me-2 text-primary"></i>
                    تغيير حالة العرض
                </h3>
                <a href="{{ route('offers.show', $offer->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle">
                    <i class="mdi mdi-close"></i>
                </a>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success d-flex align-items-center mb-4">
                    <i class="mdi mdi-check-circle-outline me-2 fs-4"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger d-flex align-items-center mb-4">
                    <i class="mdi mdi-alert-circle-outline me-2 fs-4"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="save" class="needs-validation" novalidate>
                <!-- الحالة -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        <i class="mdi mdi-state-machine me-1 text-muted"></i> الحالة الجديدة <span class="text-danger">*</span>
                    </label>
                    <select wire:model="status" class="form-select" required>
                        <option value="">اختر الحالة</option>
                        <option value="new">جديد</option>
                        <option value="under_review">تحت المتابعة</option>
                        <option value="approved">تمت الموافقة على العرض</option>
                        <option value="contracting">جارى التعاقد</option>
                        <option value="rejected">تم رفض العرض</option>
                        <option value="pending">قيد الانتظار</option>
                        <option value="signed">تم التعاقد</option>
                        <option value="closed">إغلاق العرض</option>
                    </select>
                    @error('status')
                        <div class="text-danger small mt-1 d-flex align-items-center">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- approved -->
                @if ($status === 'approved')
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="mdi mdi-calendar-send me-1 text-muted"></i> تاريخ إرسال العقد <span class="text-danger">*</span>
                        </label>
                        <input type="date" wire:model="contract_date" class="form-control" required>
                        @error('contract_date')
                            <div class="text-danger small mt-1 d-flex align-items-center">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="mdi mdi-note-text-outline me-1 text-muted"></i> ملاحظات <span class="text-danger">*</span>
                        </label>
                        <textarea wire:model="notes" class="form-control" rows="3" required></textarea>
                        @error('notes')
                            <div class="text-danger small mt-1 d-flex align-items-center">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                @endif

                <!-- rejected -->
                @if ($status === 'rejected')
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="mdi mdi-alert-box-outline me-1 text-muted"></i> سبب الرفض <span class="text-danger">*</span>
                        </label>
                        <textarea wire:model="reject_reason" class="form-control" rows="3" required></textarea>
                        @error('reject_reason')
                            <div class="text-danger small mt-1 d-flex align-items-center">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                @endif

                <!-- pending -->
                @if ($status === 'pending')
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="mdi mdi-clock-outline me-1 text-muted"></i> تاريخ الانتظار <span class="text-danger">*</span>
                        </label>
                        <input type="date" wire:model="waiting_date" class="form-control" required>
                        @error('waiting_date')
                            <div class="text-danger small mt-1 d-flex align-items-center">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                @endif

                <!-- signed -->
                @if ($status === 'signed')
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="mdi mdi-file-document-outline me-1 text-muted"></i> ملف العقد <span class="text-danger">*</span>
                        </label>
                        <input type="file" wire:model="contract_file" class="form-control" required>
                        @error('contract_file')
                            <div class="text-danger small mt-1 d-flex align-items-center">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="mdi mdi-note-edit-outline me-1 text-muted"></i> ملاحظات إدارية <span class="text-danger">*</span>
                        </label>
                        <textarea wire:model="notes" class="form-control" rows="3" required></textarea>
                        @error('notes')
                            <div class="text-danger small mt-1 d-flex align-items-center">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                @endif

                <!-- closed -->
                @if ($status === 'closed')
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="mdi mdi-close-circle-outline me-1 text-muted"></i> سبب الإغلاق <span class="text-danger">*</span>
                        </label>
                        <textarea wire:model="close_reason" class="form-control" rows="3" required></textarea>
                        @error('close_reason')
                            <div class="text-danger small mt-1 d-flex align-items-center">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4 form-check form-switch ps-0">
                        <input type="checkbox" wire:model="confirm_close" class="form-check-input ms-0 me-2"
                            id="confirmClose" style="width: 3rem; height: 1.5rem;">
                        <label class="form-check-label fw-semibold" for="confirmClose">
                            <i class="mdi mdi-shield-check-outline me-1"></i>تأكيد إغلاق العرض
                        </label>
                        @error('confirm_close')
                            <div class="text-danger small mt-1 d-flex align-items-center">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                @endif

                <!-- حفظ -->
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="mdi mdi-content-save-outline me-1"></i> حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card { border: none; overflow: hidden; }
    .card-header { border-bottom: 1px solid rgba(0, 0, 0, .05); }
    .form-select, .form-control { padding: 0.65rem 0.9rem; font-size: 0.9rem; }
    .form-select:focus, .form-control:focus { box-shadow: none; border-color: #86b7fe; }
    .rounded-4 { border-radius: 0.75rem !important; }
    .form-check-input:checked { background-color: #0d6efd; border-color: #0d6efd; }
    .rounded-pill { padding: 0.45rem 1.3rem; font-size: 0.9rem; }
    label.form-label { font-size: 0.95rem; }
</style>
