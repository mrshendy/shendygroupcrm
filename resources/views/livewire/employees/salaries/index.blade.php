<div>
    <h4>إدارة المرتبات</h4>

    {{-- رسائل التنبيه --}}
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form -->
    <form wire:submit.prevent="save" class="mb-4">
        <div class="row">
            <div class="col">
                <select wire:model="employee_id" class="form-control">
                    <option value="">اختر الموظف</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                    @endforeach
                </select>
                @error('employee_id') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col">
                <input type="month" wire:model="month" class="form-control">
                @error('month') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col">
                <input type="number" wire:model="basic_salary" class="form-control" placeholder="الراتب الأساسي">
                @error('basic_salary') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col">
                <input type="number" wire:model="allowances" class="form-control" placeholder="البدلات">
            </div>

            <div class="col">
                <input type="number" wire:model="deductions" class="form-control" placeholder="الخصومات">
            </div>

            <div class="col">
                <input type="number" wire:model="net_salary" class="form-control" placeholder="الصافي">
                @error('net_salary') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col">
                <select wire:model="status" class="form-control">
                    <option value="pending">قيد الانتظار</option>
                    <option value="paid">مدفوع</option>
                </select>
            </div>

            <div class="col">
                <input type="text" wire:model="notes" class="form-control" placeholder="ملاحظات">
            </div>

            <div class="col">
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-save"></i> 
                    {{ $salary_id ? 'تحديث' : 'حفظ' }}
                </button>
            </div>
        </div>
    </form>

    <!-- Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>الموظف</th>
                <th>الشهر</th>
                <th>الراتب الأساسي</th>
                <th>البدلات</th>
                <th>الخصومات</th>
                <th>الصافي</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($salaries as $salary)
                <tr>
                    <td>{{ $salary->employee->full_name }}</td>
                    <td>{{ $salary->month }}</td>
                    <td>{{ $salary->basic_salary }}</td>
                    <td>{{ $salary->allowances }}</td>
                    <td>{{ $salary->deductions }}</td>
                    <td>{{ $salary->net_salary }}</td>
                    <td>
                        <span class="badge {{ $salary->status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                            {{ $salary->status == 'paid' ? 'مدفوع' : 'قيد الانتظار' }}
                        </span>
                    </td>
                    <td>
                        <button wire:click="edit({{ $salary->id }})" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> تعديل
                        </button>
                        <button wire:click="delete({{ $salary->id }})" class="btn btn-danger btn-sm">
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

<style>
    h4 {
        color: #435ebe;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #435ebe;
    }

    .alert-success {
        background-color: rgba(79, 214, 156, 0.2);
        color: #4fd69c;
        border: none;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    form {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.5rem 1rem;
        border: 1px solid #e0e0e0;
        transition: all 0.3s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #435ebe;
        box-shadow: 0 0 0 0.25rem rgba(67, 94, 190, 0.25);
    }

    .btn-success {
        background-color: #4fd69c;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        transition: all 0.3s;
    }

    .btn-success:hover {
        background-color: #3dbd84;
    }

    .table {
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    }

    .table thead {
        background-color: #435ebe;
        color: white;
    }

    .table th {
        font-weight: 500;
        text-align: center;
    }

    .table td {
        vertical-align: middle;
        text-align: center;
    }

    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 6px;
    }

    .btn-primary {
        background-color: #435ebe;
        border: none;
    }

    .btn-danger {
        background-color: #ff7976;
        border: none;
    }

    .btn-primary:hover {
        background-color: #384ea0;
    }

    .btn-danger:hover {
        background-color: #e66865;
    }
</style>
