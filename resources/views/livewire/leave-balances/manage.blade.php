<div class="container-fluid p-4" dir="rtl">
    <!-- رسائل التنبيه -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 shadow-sm border-0 bg-opacity-10 bg-success">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-check-circle-outline me-2 fs-4 text-success"></i>
                <div class="fw-medium text-success">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- العنوان + أدوات البحث -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="mb-1 fw-bold text-primary">
                <i class="mdi mdi-beach me-2"></i>إدارة أرصدة الإجازات
            </h4>
            <div class="text-muted small opacity-75">إنشاء/تعديل رصيد الإجازات، وتسجيل إجازة جديدة</div>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <div class="input-group input-group-merged">
                <span class="input-group-text bg-light"><i class="mdi mdi-magnify"></i></span>
                <input type="text" class="form-control" placeholder="بحث بالاسم أو السنة..." 
                       wire:model.debounce.500ms="search">
            </div>
            <select class="form-select" style="min-width:120px" wire:model="perPage">
                <option value="10">10 صفوف</option>
                <option value="25">25 صفًا</option>
                <option value="50">50 صفًا</option>
                <option value="100">100 صف</option>
            </select>
        </div>
    </div>

    <!-- فورم رصيد الإجازات -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light fw-bold py-3 d-flex align-items-center">
            <i class="mdi mdi-account-cog-outline me-2 text-primary"></i>
            {{ $leave_balance_id ? 'تعديل رصيد الإجازات' : 'إضافة رصيد إجازات' }}
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- حقول الفورم -->
                <div class="col-md-4">
                    <label class="form-label fw-medium"><i class="mdi mdi-account-outline me-1 text-muted"></i>الموظف</label>
                    <select class="form-select" wire:model="employee_id">
                        <option value="">— اختر الموظف —</option>
                        @foreach ($employees as $e)
                            <option value="{{ $e->id }}">{{ $e->full_name }}</option>
                        @endforeach
                    </select>
                    @error('employee_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-medium"><i class="mdi mdi-calendar me-1 text-muted"></i>السنة</label>
                    <input type="number" min="2000" max="2100" class="form-control" wire:model="year">
                    @error('year') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-medium"><i class="mdi mdi-clock-outline me-1 text-muted"></i>الشيفت</label>
                    <select class="form-select" wire:model="shift_id">
                        <option value="">— اختر الشيفت —</option>
                        @foreach ($shifts as $s)
                            <option value="{{ $s['id'] }}">{{ $s['name'] }}</option>
                        @endforeach
                    </select>
                    @error('shift_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-medium"><i class="mdi mdi-calendar-multiple me-1 text-muted"></i>إجمالي الأيام</label>
                    <input type="number" class="form-control" wire:model.lazy="total_days" min="0" max="365">
                    @error('total_days') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-medium"><i class="mdi mdi-calendar-check me-1 text-muted"></i>المستخدمة</label>
                    <input type="number" class="form-control" wire:model.lazy="used_days" min="0">
                    @error('used_days') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-medium"><i class="mdi mdi-calendar-remove me-1 text-muted"></i>المتبقية</label>
                    <input type="number" class="form-control" wire:model="remaining_days" readonly>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-medium"><i class="mdi mdi-palm-tree me-1 text-muted"></i>اعتيادية</label>
                    <input type="number" class="form-control" wire:model.lazy="annual_days" min="0">
                    @error('annual_days') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-medium"><i class="mdi mdi-run-fast me-1 text-muted"></i>عارضة</label>
                    <input type="number" class="form-control" wire:model.lazy="casual_days" min="0">
                    @error('casual_days') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 d-flex gap-2 mt-2">
                    <button type="button" class="btn btn-primary px-4" wire:click="save">
                        <i class="mdi mdi-content-save-outline me-1"></i>
                        حفظ الرصيد
                    </button>
                    <button type="button" class="btn btn-outline-secondary" wire:click="resetForm">
                        <i class="mdi mdi-autorenew me-1"></i>
                        تفريغ النموذج
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول أرصدة الإجازات -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light fw-bold py-3 d-flex align-items-center">
            <i class="mdi mdi-table me-2 text-primary"></i>
            أرصدة الإجازات
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-bold" style="cursor:pointer" wire:click="sortBy('year')">
                            <i class="mdi mdi-calendar me-1 text-muted"></i> السنة
                            @if($sortField === 'year')
                                <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                            @endif
                        </th>
                        <th class="fw-bold"><i class="mdi mdi-account-outline me-1 text-muted"></i>الموظف</th>
                        <th class="fw-bold"><i class="mdi mdi-clock-outline me-1 text-muted"></i>الشيفت</th>
                        <th class="fw-bold" style="cursor:pointer" wire:click="sortBy('total_days')">
                            <i class="mdi mdi-calendar-multiple me-1 text-muted"></i> الإجمالي
                            @if($sortField === 'total_days')
                                <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                            @endif
                        </th>
                        <th class="fw-bold" style="cursor:pointer" wire:click="sortBy('used_days')">
                            <i class="mdi mdi-calendar-check me-1 text-muted"></i> المستخدمة
                            @if($sortField === 'used_days')
                                <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                            @endif
                        </th>
                        <th class="fw-bold"><i class="mdi mdi-calendar-remove me-1 text-muted"></i>المتبقية</th>
                        <th class="fw-bold"><i class="mdi mdi-palm-tree me-1 text-muted"></i>سنوية</th>
                        <th class="fw-bold"><i class="mdi mdi-run-fast me-1 text-muted"></i>عارضة</th>
                        <th class="fw-bold text-nowrap"><i class="mdi mdi-cog-outline me-1 text-muted"></i>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->year }}</td>
                            <td>{{ $item->employee->full_name ?? '-' }}</td>
                            <td>{{ optional($item->employee->shift)->name ?? '-' }}</td>
                            <td>{{ $item->total_days }}</td>
                            <td>{{ $item->used_days }}</td>
                            <td>{{ $item->remaining_days }}</td>
                            <td>{{ $item->annual_days }}</td>
                            <td>{{ $item->casual_days }}</td>
                            <td class="text-nowrap">
                                <button class="btn btn-sm btn-icon btn-outline-danger"
                                        onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                        wire:click="delete({{ $item->id }})" title="حذف">
                                    <i class="mdi mdi-trash-can-outline"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="mdi mdi-database-remove me-2"></i> لا توجد بيانات
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light">
            {{ $items->links() }}
        </div>
    </div>

    <!-- فورم تسجيل الإجازة -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-light fw-bold py-3 d-flex align-items-center">
            <i class="mdi mdi-beach me-2 text-primary"></i>
            {{ $leave_id ? 'تعديل طلب إجازة' : 'تسجيل إجازة جديدة' }}
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- حقول الفورم -->
                <div class="col-md-4">
                    <label class="form-label fw-medium"><i class="mdi mdi-account-outline me-1 text-muted"></i>الموظف</label>
                    <select class="form-select" wire:model="leave_employee_id">
                        <option value="">— اختر موظف —</option>
                        @foreach ($employees as $e)
                            <option value="{{ $e->id }}">{{ $e->full_name }}</option>
                        @endforeach
                    </select>
                    @error('leave_employee_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium"><i class="mdi mdi-clock-outline me-1 text-muted"></i>الشيفت</label>
                    <select class="form-select" wire:model="leave_shift_id">
                        <option value="">— اختر الشيفت —</option>
                        @foreach ($shifts as $s)
                            <option value="{{ $s['id'] }}">{{ $s['name'] }}</option>
                        @endforeach
                    </select>
                    @error('leave_shift_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium"><i class="mdi mdi-tag-outline me-1 text-muted"></i>نوع الإجازة</label>
                    <select class="form-select" wire:model="leave_type">
                        <option value="annual">اعتيادية</option>
                        <option value="casual">عارضة</option>
                        <option value="sick">مرضية</option>
                        <option value="unpaid">بدون أجر</option>
                        <option value="other">أخرى</option>
                    </select>
                    @error('leave_type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-medium"><i class="mdi mdi-calendar-start me-1 text-muted"></i>من</label>
                    <input type="date" class="form-control" wire:model="leave_start_date">
                    @error('leave_start_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-medium"><i class="mdi mdi-calendar-end me-1 text-muted"></i>إلى</label>
                    <input type="date" class="form-control" wire:model="leave_end_date">
                    @error('leave_end_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-medium"><i class="mdi mdi-check-circle-outline me-1 text-muted"></i>الحالة</label>
                    <select class="form-select" wire:model="leave_status">
                        <option value="approved">مُعتمدة</option>
                        <option value="pending">معلّقة</option>
                        <option value="rejected">مرفوضة</option>
                        <option value="cancelled">ملغاة</option>
                    </select>
                    @error('leave_status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-medium"><i class="mdi mdi-note-text-outline me-1 text-muted"></i>سبب/ملاحظات</label>
                    <textarea class="form-control" rows="2" wire:model="leave_reason"></textarea>
                    @error('leave_reason') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="button" class="btn btn-success px-4" wire:click="saveLeave">
                        <i class="mdi mdi-content-save-outline me-1"></i>
                        حفظ الإجازة
                    </button>
                    <button type="button" class="btn btn-outline-secondary" wire:click="resetLeaveForm">
                        <i class="mdi mdi-autorenew me-1"></i>
                        إلغاء/تفريغ
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08) !important;
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .table th {
        white-space: nowrap;
        padding: 12px 16px;
        border-bottom: 2px solid #f5f5f5;
    }
    
    .table td {
        padding: 14px 16px;
        vertical-align: middle;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.03) !important;
    }
    
    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
    
    .input-group-merged .form-control,
    .input-group-merged .form-select {
        border-left: 0 !important;
    }
    
    .input-group-merged .input-group-text {
        border-right: 0 !important;
        background-color: #f8f9fa;
    }
    
    .form-label {
        font-size: 0.85rem;
        margin-bottom: 0.4rem;
    }
    
    .text-primary {
        color: #5d87ff !important;
    }
    
    .bg-light {
        background-color: #f9fafc !important;
    }
</style>