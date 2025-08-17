<div>
    <!-- تنبيهات -->
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form wire:submit.prevent="update">
        <div class="mb-3">
            <label class="form-label">الموظف</label>
            <select wire:model="employee_id" class="form-control">
                <option value="">-- اختر موظف --</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                @endforeach
            </select>
            @error('employee_id') <span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">الشهر</label>
            <input type="date" wire:model="month" class="form-control">
            @error('month') <span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">الراتب الأساسي</label>
                <input type="number" step="0.01" wire:model="basic_salary" class="form-control">
                @error('basic_salary') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">البدلات</label>
                <input type="number" step="0.01" wire:model="allowances" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">الخصومات</label>
                <input type="number" step="0.01" wire:model="deductions" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">صافي الراتب</label>
                <input type="number" step="0.01" wire:model="net_salary" class="form-control" readonly>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">الحالة</label>
            <select wire:model="status" class="form-control">
                <option value="pending">قيد الانتظار</option>
                <option value="approved">موافق عليه</option>
                <option value="paid">تم الدفع</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">ملاحظات</label>
            <textarea wire:model="notes" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="mdi mdi-content-save"></i> تحديث
        </button>
        <a href="{{ route('employees.salaries') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
