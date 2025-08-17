<div class="container-fluid p-4">
    <!-- رسائل الفلاش - تصميم محسن -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="mdi mdi-check-circle-outline me-2 fs-4"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="mdi mdi-alert-circle-outline me-2 fs-4"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- بطاقة نموذج تقديم الإجازة -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0 fw-bold">
                <i class="mdi mdi-beach me-2"></i>
                تقديم طلب إجازة جديد
            </h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="save" class="needs-validation" novalidate>
                <div class="row g-3">
                    <!-- حقل نوع الإجازة -->
                    <div class="col-md-6">
                        <label for="leave_type" class="form-label fw-bold">نوع الإجازة</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="mdi mdi-format-list-checks"></i></span>
                            <select wire:model="leave_type" id="leave_type" class="form-select">
                                <option value="annual">إجازة سنوية</option>
                                <option value="casual">إجازة عارضة</option>
                                <option value="sick">إجازة مرضية</option>
                                <option value="unpaid">إجازة بدون راتب</option>
                                <option value="other">إجازة أخرى</option>
                            </select>
                        </div>
                        @error('leave_type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <!-- حقل تاريخ البداية -->
                    <div class="col-md-3">
                        <label for="start_date" class="form-label fw-bold">تاريخ البداية</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="mdi mdi-calendar-start"></i></span>
                            <input type="date" wire:model="start_date" id="start_date" class="form-control">
                        </div>
                        @error('start_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <!-- حقل تاريخ النهاية -->
                    <div class="col-md-3">
                        <label for="end_date" class="form-label fw-bold">تاريخ النهاية</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="mdi mdi-calendar-end"></i></span>
                            <input type="date" wire:model="end_date" id="end_date" class="form-control">
                        </div>
                        @error('end_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <!-- حقل السبب -->
                    <div class="col-12">
                        <label for="reason" class="form-label fw-bold">السبب <small class="text-muted">(اختياري)</small></label>
                        <textarea wire:model="reason" id="reason" rows="3" class="form-control" placeholder="يمكنك كتابة سبب الإجازة هنا..."></textarea>
                        @error('reason') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <!-- زر التقديم -->
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="mdi mdi-send-check me-1"></i> تقديم الطلب
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- بطاقة طلبات الإجازة السابقة -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0 fw-bold">
                <i class="mdi mdi-history me-2"></i>
                طلبات الإجازة السابقة
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold">النوع</th>
                            <th class="fw-bold">من</th>
                            <th class="fw-bold">إلى</th>
                            <th class="fw-bold">المدة</th>
                            <th class="fw-bold text-center">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $employeeId = Auth::user()->employee_id ?? null;
                            $leaves = \App\Models\Leave::where('employee_id', $employeeId)
                                        ->orderBy('start_date','desc')->limit(5)->get();
                        @endphp

                        @forelse ($leaves as $leave)
                            <tr>
                                <td>
                                    @switch($leave->leave_type)
                                        @case('annual') <i class="mdi mdi-calendar-star me-1 text-success"></i> سنوية @break
                                        @case('casual') <i class="mdi mdi-calendar-blank me-1 text-primary"></i> عارضة @break
                                        @case('sick') <i class="mdi mdi-hospital-box me-1 text-danger"></i> مرضية @break
                                        @case('unpaid') <i class="mdi mdi-cash-remove me-1 text-warning"></i> بدون راتب @break
                                        @default <i class="mdi mdi-calendar-question me-1 text-info"></i> أخرى
                                    @endswitch
                                </td>
                                <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('Y-m-d') }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('Y-m-d') }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays($leave->end_date) + 1 }} يوم
                                </td>
                                <td class="text-center">
                                    @if ($leave->status == 'pending')
                                        <span class="badge bg-warning text-dark rounded-pill py-2 px-3">
                                            <i class="mdi mdi-clock-outline me-1"></i> قيد المراجعة
                                        </span>
                                    @elseif ($leave->status == 'approved')
                                        <span class="badge bg-success rounded-pill py-2 px-3">
                                            <i class="mdi mdi-check-circle-outline me-1"></i> موافق عليها
                                        </span>
                                    @elseif ($leave->status == 'rejected')
                                        <span class="badge bg-danger rounded-pill py-2 px-3">
                                            <i class="mdi mdi-close-circle-outline me-1"></i> مرفوضة
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="mdi mdi-beach-off fs-1"></i>
                                    <p class="mt-2">لا توجد طلبات إجازة سابقة</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 0.5rem;
    }
    
    .table th {
        white-space: nowrap;
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    .form-select, .form-control {
        border-radius: 0.375rem;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
    }
    
    textarea {
        min-height: 100px;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // إضافة تأثيرات للتاريخ عند التركيز
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.type = 'date';
            });
            input.addEventListener('blur', function() {
                if (!this.value) this.type = 'text';
            });
        });
    });
</script>