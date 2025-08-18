<div class="container-fluid" dir="rtl">
    <h4 class="page-title">إدارة المرتبات</h4>

    {{-- رسائل --}}
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- شريط البحث/الفلترة --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label">بحث (اسم الموظف أو الشهر)</label>
                    <input type="text" class="form-control" placeholder="مثال: أحمد / 2025-08"
                           wire:model.debounce.400ms="search">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">فلترة بالشهر</label>
                    <input type="month" class="form-control" wire:model="filter_month">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">الحالة</label>
                    <select class="form-select" wire:model="filter_status">
                        <option value="">الكل</option>
                        <option value="pending">قيد الانتظار</option>
                        <option value="paid">مدفوع</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <button type="button" class="btn btn-outline-secondary" wire:click="clearFilters">
                        مسح البحث
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- نموذج الإدخال --}}
    <form wire:submit.prevent="save" class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-4 col-lg-3">
                    <label class="form-label">الموظف</label>
                    <select wire:model="employee_id" class="form-select">
                        <option value="">اختر الموظف</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                    @error('employee_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label">الشهر</label>
                    <input type="month" wire:model="month" class="form-control">
                    @error('month') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label">الأساسي</label>
                    <div class="input-group">
                        <span class="input-group-text">ج.م</span>
                        <input type="number" step="0.01" min="0"
                               wire:model.debounce.300ms="basic_salary"
                               class="form-control" placeholder="0.00">
                    </div>
                    @error('basic_salary') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label">البدلات</label>
                    <div class="input-group">
                        <span class="input-group-text">ج.م</span>
                        <input type="number" step="0.01" min="0"
                               wire:model.debounce.300ms="allowances"
                               class="form-control" placeholder="0.00">
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label">الخصومات</label>
                    <div class="input-group">
                        <span class="input-group-text">ج.م</span>
                        <input type="number" step="0.01" min="0"
                               wire:model.debounce.300ms="deductions"
                               class="form-control" placeholder="0.00">
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <label class="form-label">الصافي</label>
                    <div class="input-group">
                        <span class="input-group-text">ج.م</span>
                        {{-- محسوب تلقائيًا --}}
                        <input type="number" step="0.01" wire:model="net_salary"
                               class="form-control bg-light" readonly>
                    </div>
                    @error('net_salary') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <label class="form-label">الحالة</label>
                    <select wire:model="status" class="form-select">
                        <option value="pending">قيد الانتظار</option>
                        <option value="paid">مدفوع</option>
                    </select>
                </div>

                <div class="col-12 col-md-8 col-lg-5">
                    <label class="form-label">ملاحظات</label>
                    <input type="text" wire:model="notes" class="form-control" placeholder="اختياري">
                </div>

                <div class="col-6 col-md-2 d-grid">
                    <label class="form-label d-none d-md-block">&nbsp;</label>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> {{ $salary_id ? 'تحديث' : 'حفظ' }}
                    </button>
                </div>

                <div class="col-6 col-md-2 d-grid">
                    <label class="form-label d-none d-md-block">&nbsp;</label>
                    <button type="button" class="btn btn-outline-secondary" wire:click="resetInput">
                        تفريغ
                    </button>
                </div>
            </div>

            {{-- معادلة سريعة أسفل النموذج --}}
            <div class="text-muted small mt-3">
                الصافي = الأساسي + البدلات − الخصومات =&nbsp;
                <strong>{{ number_format((float)$net_salary, 2) }}</strong>
            </div>
        </div>
    </form>

    {{-- الجدول --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>الموظف</th>
                        <th>الشهر</th>
                        <th>الأساسي</th>
                        <th>البدلات</th>
                        <th>الخصومات</th>
                        <th>الصافي</th>
                        <th>الحالة</th>
                        <th style="width:160px">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salaries as $salary)
                        <tr>
                            <td>{{ $salary->employee->full_name ?? '-' }}</td>
                            <td>{{ $salary->month }}</td>
                            <td>{{ number_format((float)$salary->basic_salary, 2) }}</td>
                            <td>{{ number_format((float)$salary->allowances, 2) }}</td>
                            <td>{{ number_format((float)$salary->deductions, 2) }}</td>
                            <td class="fw-bold">{{ number_format((float)$salary->net_salary, 2) }}</td>
                            <td>
                                <span class="badge {{ $salary->status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $salary->status === 'paid' ? 'مدفوع' : 'قيد الانتظار' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button wire:click="edit({{ $salary->id }})" class="btn btn-primary btn-sm me-1">
                                    <i class="fas fa-edit"></i> تعديل
                                </button>
                                <button wire:click="delete({{ $salary->id }})" class="btn btn-danger btn-sm"
                                        onclick="return confirm('تأكيد حذف السجل؟')">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">لا توجد بيانات مرتبات مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .page-title{color:#435ebe;margin-bottom:16px;padding-bottom:8px;border-bottom:2px solid #435ebe}
    .alert-success{background:rgba(79,214,156,.15);color:#2e8b6d;border:none;border-radius:8px}
    .card{border:0;border-radius:10px}
    .form-control,.form-select{border-radius:8px;border:1px solid #e0e0e0}
    .form-control:focus,.form-select:focus{border-color:#435ebe;box-shadow:0 0 0 .2rem rgba(67,94,190,.15)}
    .btn-success{background:#4fd69c;border:none;border-radius:8px}
    .btn-success:hover{background:#3dbd84}
    .btn-primary{background:#435ebe;border:none;border-radius:8px}
    .btn-primary:hover{background:#384ea0}
    .btn-danger{background:#ff7976;border:none;border-radius:8px}
    .btn-danger:hover{background:#e66865}
    .table thead{background:#435ebe;color:#fff}
    .table th,.table td{text-align:center;vertical-align:middle}
</style>
