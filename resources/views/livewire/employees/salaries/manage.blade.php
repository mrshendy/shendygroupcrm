<div class="container" dir="rtl">
    <h3 class="mb-3">إدارة مرتبات الموظفين</h3>

    {{-- رسائل فلاش --}}
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center">
            <i class="mdi mdi-check-circle-outline me-2"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- نموذج إضافة / تعديل مرتب --}}
    <div class="card mb-4">
        <div class="card-header fw-bold">{{ $salary_id ? 'تعديل مرتب' : 'إضافة مرتب جديد' }}</div>
        <div class="card-body row g-3">
            <div class="col-md-4">
                <label class="form-label">الموظف <span class="text-danger">*</span></label>
                <select class="form-select" wire:model="employee_id">
                    <option value="">اختر موظف</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                    @endforeach
                </select>
                @error('employee_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">الشهر <span class="text-danger">*</span></label>
                <input type="month" class="form-control" wire:model="month">
                @error('month') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">الراتب الأساسي</label>
                <input type="number" step="0.01" class="form-control" wire:model="basic_salary">
                @error('basic_salary') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">البدلات</label>
                <input type="number" step="0.01" class="form-control" wire:model="allowances">
                @error('allowances') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">الخصومات</label>
                <input type="number" step="0.01" class="form-control" wire:model="deductions">
                @error('deductions') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">الحالة</label>
                <select class="form-select" wire:model="status">
                    <option value="pending">قيد الانتظار</option>
                    <option value="paid">مدفوع</option>
                </select>
                @error('status') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-12">
                <label class="form-label">ملاحظات</label>
                <textarea class="form-control" wire:model="notes"></textarea>
            </div>

            <div class="col-12 text-end">
                <button class="btn btn-success" wire:click="save">حفظ</button>
                @if($salary_id)
                    <button class="btn btn-secondary" wire:click="resetForm">إلغاء</button>
                @endif
            </div>
        </div>
    </div>

    {{-- جدول المرتبات --}}
    <div class="card">
        <div class="card-header fw-bold">قائمة المرتبات</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>الموظف</th>
                        <th>الشهر</th>
                        <th>الأساسي</th>
                        <th>البدلات</th>
                        <th>الخصومات</th>
                        <th>الصافي</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salaries as $sal)
                        <tr>
                            <td>{{ $sal->employee->full_name }}</td>
                            <td>{{ $sal->month }}</td>
                            <td>{{ number_format($sal->basic_salary, 2) }}</td>
                            <td>{{ number_format($sal->allowances, 2) }}</td>
                            <td>{{ number_format($sal->deductions, 2) }}</td>
                            <td>{{ number_format($sal->net_salary, 2) }}</td>
                            <td>
                                @if($sal->status === 'paid')
                                    <span class="badge bg-success">مدفوع</span>
                                @else
                                    <span class="badge bg-warning">قيد الانتظار</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" wire:click="edit({{ $sal->id }})">تعديل</button>
                                <button class="btn btn-sm btn-danger" wire:click="delete({{ $sal->id }})">حذف</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">لا توجد بيانات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
