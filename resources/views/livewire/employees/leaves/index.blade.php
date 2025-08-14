<div>
    <h4>إدارة الإجازات</h4>

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
                <select wire:model="type" class="form-control">
                    <option value="annual">سنوية</option>
                    <option value="sick">مرضي</option>
                    <option value="emergency">طارئة</option>
                </select>
            </div>
            <div class="col">
                <input type="date" wire:model="start_date" class="form-control">
            </div>
            <div class="col">
                <input type="date" wire:model="end_date" class="form-control">
            </div>
            <div class="col">
                <input type="number" wire:model="days_count" class="form-control" placeholder="عدد الأيام">
            </div>
            <div class="col">
                <input type="text" wire:model="reason" class="form-control" placeholder="السبب">
            </div>
            <div class="col">
                <select wire:model="status" class="form-control">
                    <option value="pending">قيد الانتظار</option>
                    <option value="approved">مقبولة</option>
                    <option value="rejected">مرفوضة</option>
                </select>
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
                <th>النوع</th>
                <th>من</th>
                <th>إلى</th>
                <th>عدد الأيام</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaves as $leave)
                <tr>
                    <td>{{ $leave->employee->full_name }}</td>
                    <td>{{ $leave->type }}</td>
                    <td>{{ $leave->start_date }}</td>
                    <td>{{ $leave->end_date }}</td>
                    <td>{{ $leave->days_count }}</td>
                    <td>{{ $leave->status }}</td>
                    <td>
                        <button wire:click="edit({{ $leave->id }})" class="btn btn-primary btn-sm">تعديل</button>
                        <button wire:click="delete({{ $leave->id }})" class="btn btn-danger btn-sm">حذف</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
