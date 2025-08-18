{{-- resources/views/livewire/leave-balances/manage.blade.php --}}
<div class="container-fluid px-3" dir="rtl">

    {{-- رسائل التنبيه --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show my-3 rounded-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-check-circle-outline me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- العنوان + أدوات البحث --}}
    <div class="d-flex flex-wrap justify-content-between align-items-end my-3 gap-3">
        <div>
            <h4 class="mb-1">إدارة أرصدة الإجازات</h4>
            <div class="text-muted small">إنشاء/تعديل الرصيد، وتسجيل إجازة سريعة من نفس الشاشة.</div>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <div class="input-group">
                <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                <input type="text" class="form-control" placeholder="بحث بالاسم أو السنة..."
                       wire:model.debounce.500ms="search">
            </div>
            <select class="form-select" style="min-width:120px" wire:model="perPage" title="عدد الصفوف">
                <option value="10">10 صفوف</option>
                <option value="25">25 صفًا</option>
                <option value="50">50 صفًا</option>
                <option value="100">100 صف</option>
            </select>
        </div>
    </div>


    {{-- فورم رصيد الإجازات --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header fw-bold">
            {{ $leave_balance_id ? 'تعديل رصيد الإجازات' : 'إضافة رصيد إجازات' }}
        </div>
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">الموظف</label>
                    <select class="form-select" wire:model="employee_id">
                        <option value="">— اختر الموظف —</option>
                        @foreach ($employees as $e)
                            <option value="{{ $e->id }}">{{ $e->full_name }}</option>
                        @endforeach
                    </select>
                    @error('employee_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label">السنة</label>
                    <input type="number" min="2000" max="2100" class="form-control" wire:model="year">
                    @error('year') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">الشيفت</label>
                    <select class="form-select" wire:model="shift_id">
                        <option value="">— اختر الشيفت —</option>
                        @foreach ($shifts as $s)
                            <option value="{{ $s['id'] }}">{{ $s['name'] }}</option>
                        @endforeach
                    </select>
                    @error('shift_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">إجمالي الأيام</label>
                    <input type="number" class="form-control" wire:model.lazy="total_days" min="0" max="365">
                    @error('total_days') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">المستخدمة</label>
                    <input type="number" class="form-control" wire:model.lazy="used_days" min="0">
                    @error('used_days') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">المتبقية</label>
                    <input type="number" class="form-control" wire:model="remaining_days" readonly>
                </div>

                <div class="col-md-3">
                    <label class="form-label">اعتيادية </label>
                    <input type="number" class="form-control" wire:model.lazy="annual_days" min="0">
                    @error('annual_days') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">عارضة </label>
                    <input type="number" class="form-control" wire:model.lazy="casual_days" min="0">
                    @error('casual_days') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 d-flex gap-2 mt-2">
                    <button type="button" class="btn btn-primary" wire:click="save">
                        <i class="mdi mdi-content-save-outline me-1"></i>
                        حفظ الرصيد
                    </button>
                    <button type="button" class="btn btn-outline-secondary" wire:click="resetForm">
                        تفريغ النموذج
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- جدول أرصدة الإجازات --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header fw-bold">أرصدة الإجازات</div>
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th style="cursor:pointer" wire:click="sortBy('year')">
                            السنة
                            @if($sortField === 'year')
                                <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th>الموظف</th>
                        <th>الشيفت</th>
                        <th style="cursor:pointer" wire:click="sortBy('total_days')">
                            الإجمالي
                            @if($sortField === 'total_days')
                                <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th style="cursor:pointer" wire:click="sortBy('used_days')">
                            المستخدمة
                            @if($sortField === 'used_days')
                                <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th>المتبقية</th>
                        <th>سنوية</th>
                        <th>عارضة</th>
                        <th class="text-nowrap">إجراءات</th>
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

                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                        wire:click="delete({{ $item->id }})" title="حذف">
                                    <i class="mdi mdi-trash-can-outline"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">لا توجد بيانات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $items->links() }}
        </div>
    </div>

    {{-- فورم تسجيل/تعديل الإجازة --}}
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header fw-bold">
            {{ $leave_id ? 'تعديل طلب إجازة' : 'تسجيل إجازة جديدة' }}
        </div>
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">الموظف</label>
                    <select class="form-select" wire:model="leave_employee_id">
                        <option value="">— اختر موظف —</option>
                        @foreach ($employees as $e)
                            <option value="{{ $e->id }}">{{ $e->full_name }}</option>
                        @endforeach
                    </select>
                    @error('leave_employee_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">الشيفت</label>
                    <select class="form-select" wire:model="leave_shift_id">
                        <option value="">— اختر الشيفت —</option>
                        @foreach ($shifts as $s)
                            <option value="{{ $s['id'] }}">{{ $s['name'] }}</option>
                        @endforeach
                    </select>
                    @error('leave_shift_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">نوع الإجازة</label>
                    <select class="form-select" wire:model="leave_type">
                        <option value="annual">اعتيادية</option>
                        <option value="casual">عارضة</option>
                        <option value="sick">مرضية</option>
                        <option value="unpaid">بدون أجر</option>
                        <option value="other">أخرى</option>
                    </select>
                    @error('leave_type') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">من</label>
                    <input type="date" class="form-control" wire:model="leave_start_date">
                    @error('leave_start_date') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">إلى</label>
                    <input type="date" class="form-control" wire:model="leave_end_date">
                    @error('leave_end_date') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">الحالة</label>
                    <select class="form-select" wire:model="leave_status">
                        <option value="approved">مُعتمدة</option>
                        <option value="pending">معلّقة</option>
                        <option value="rejected">مرفوضة</option>
                        <option value="cancelled">ملغاة</option>
                    </select>
                    @error('leave_status') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">سبب/ملاحظات</label>
                    <textarea class="form-control" rows="2" wire:model="leave_reason"></textarea>
                    @error('leave_reason') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="button" class="btn btn-success" wire:click="saveLeave">
                        <i class="mdi mdi-content-save-outline me-1"></i>
                        حفظ الإجازة
                    </button>
                    <button type="button" class="btn btn-outline-secondary" wire:click="resetLeaveForm">
                        إلغاء/تفريغ
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>
