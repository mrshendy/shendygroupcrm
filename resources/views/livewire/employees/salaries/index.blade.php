<div>
    <h4>إدارة المرتبات</h4>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" class="mb-4">
        <div class="row">
            <div class="col">
                <select wire:model="employee_id" class="form-control">
                    <option value="">اختر الموظف</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <input type="month" wire:model="month" class="form-control">
            </div>
            <div class="col">
                <input type="number" wire:model="basic_salary" class="form-control" placeholder="الراتب الأساسي">
            </div>
            <div class="col">
                <input type="number" wire:model="allowances" class="form-control" placeholder="البدلات">
            </div>
            <div class="col">
                <input type="number" wire:model="deductions" class="form-control" placeholder="الخصومات">
            </div>
            <div class="col">
                <input type="number" wire:model="net_salary" class="form-control" placeholder="الصافي">
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
                <button type="submit" class="btn btn-success">حفظ</button>
            </div>
        </div>
    </form>

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
            @foreach($salaries as $salary)
                <tr>
                    <td>{{ $salary->employee->full_name }}</td>
                    <td>{{ $salary->month }}</td>
                    <td>{{ $salary->basic_salary }}</td>
                    <td>{{ $salary->allowances }}</td>
                    <td>{{ $salary->deductions }}</td>
                    <td>{{ $salary->net_salary }}</td>
                    <td>{{ $salary->status }}</td>
                    <td>
                        <button wire:click="edit({{ $salary->id }})" class="btn btn-primary btn-sm">تعديل</button>
                        <button wire:click="delete({{ $salary->id }})" class="btn btn-danger btn-sm">حذف</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
