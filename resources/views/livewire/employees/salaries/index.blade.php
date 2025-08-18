<div>
    <h4>إدارة المرتبات</h4>

    {{-- رسائل --}}
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- نموذج --}}
    <form wire:submit.prevent="save" class="mb-4">
        <div class="row g-2">

            <div class="col-md">
                <select wire:model="employee_id" class="form-select">
                    <option value="">اختر الموظف</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                    @endforeach
                </select>
                @error('employee_id') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md">
                <input type="month" wire:model="month" class="form-control">
                @error('month') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md">
                <div class="input-group">
                    <span class="input-group-text">الأساسي</span>
                    <input type="number" step="0.01" min="0"
                           wire:model.debounce.300ms="basic_salary"
                           class="form-control" placeholder="الراتب الأساسي">
                </div>
                @error('basic_salary') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md">
                <div class="input-group">
                    <span class="input-group-text">البدلات</span>
                    <input type="number" step="0.01" min="0"
                           wire:model.debounce.300ms="allowances"
                           class="form-control" placeholder="0.00">
                </div>
            </div>

            <div class="col-md">
                <div class="input-group">
                    <span class="input-group-text">الخصومات</span>
                    <input type="number" step="0.01" min="0"
                           wire:model.debounce.300ms="deductions"
                           class="form-control" placeholder="0.00">
                </div>
            </div>

            <div class="col-md">
                <div class="input-group">
                    <span class="input-group-text">الصافي</span>
                    {{-- الصافي محسوب تلقائيًا ويُحدّث لحظيًا --}}
                    <input type="number" step="0.01" wire:model="net_salary" class="form-control bg-light" readonly>
                </div>
                @error('net_salary') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-2">
                <select wire:model="status" class="form-select">
                    <option value="pending">قيد الانتظار</option>
                    <option value="paid">مدفوع</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="text" wire:model="notes" class="form-control" placeholder="ملاحظات">
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-save"></i> {{ $salary_id ? 'تحديث' : 'حفظ' }}
                </button>
            </div>

            <div class="col-md-2">
                <button type="button" class="btn btn-outline-secondary w-100" wire:click="resetInput">
                    تفريغ
                </button>
            </div>
        </div>

        {{-- شريط معادلة صغير --}}
        <div class="text-muted small mt-2">
            الصافي = الأساسي + البدلات − الخصومات =&nbsp;
            <strong>{{ number_format((float)$net_salary, 2) }}</strong>
        </div>
    </form>

    {{-- جدول --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
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
                        <td colspan="8" class="text-center text-muted">لا توجد بيانات مرتبات مسجلة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    h4{color:#435ebe;margin-bottom:20px;padding-bottom:10px;border-bottom:2px solid #435ebe}
    .alert-success{background-color:rgba(79,214,156,.2);color:#4fd69c;border:none;border-radius:8px;margin-bottom:20px}
    form{background:#fff;padding:20px;border-radius:10px;box-shadow:0 0 15px rgba(0,0,0,.05);margin-bottom:30px}
    .form-control,.form-select{border-radius:8px;padding:.5rem 1rem;border:1px solid #e0e0e0;transition:.3s}
    .form-control:focus,.form-select:focus{border-color:#435ebe;box-shadow:0 0 0 .25rem rgba(67,94,190,.25)}
    .btn-success{background:#4fd69c;border:none;border-radius:8px;padding:.5rem 1.5rem;transition:.3s}
    .btn-success:hover{background:#3dbd84}
    .table{background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 0 15px rgba(0,0,0,.05)}
    .table thead{background:#435ebe;color:#fff}
    .table th,.table td{text-align:center;vertical-align:middle}
    .btn-sm{padding:.25rem .75rem;font-size:.875rem;border-radius:6px}
    .btn-primary{background:#435ebe;border:none}
    .btn-primary:hover{background:#384ea0}
    .btn-danger{background:#ff7976;border:none}
    .btn-danger:hover{background:#e66865}
    
</style>
