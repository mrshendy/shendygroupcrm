<div class="container" dir="rtl">
    <h3 class="mb-3">إدارة الإجازات</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" class="card mb-4 p-3">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">الموظف</label>
                <select class="form-select" wire:model="employee_id">
                    <option value="">اختر</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                    @endforeach
                </select>
                @error('employee_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-2">
                <label class="form-label">نوع الإجازة</label>
                <select class="form-select" wire:model="type">
                    <option value="annual">سنوية</option>
                    <option value="sick">مرضية</option>
                    <option value="emergency">طارئة</option>
                </select>
                @error('type') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-2">
                <label class="form-label">تاريخ البداية</label>
                <input type="date" class="form-control" wire:model="start_date">
                @error('start_date') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-2">
                <label class="form-label">تاريخ النهاية</label>
                <input type="date" class="form-control" wire:model="end_date">
                @error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-1">
                <label class="form-label">الأيام</label>
                <input type="number" class="form-control" wire:model="days_count">
                @error('days_count') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-2">
                <label class="form-label">الحالة</label>
                <select class="form-select" wire:model="status">
                    <option value="pending">قيد الانتظار</option>
                    <option value="approved">موافقة</option>
                    <option value="rejected">مرفوضة</option>
                </select>
                @error('status') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-12">
                <label class="form-label">السبب</label>
                <textarea class="form-control" wire:model="reason"></textarea>
                @error('reason') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="mt-3 text-end">
            <button class="btn btn-success">حفظ</button>
        </div>
    </form>

    <div class="card">
        <div class="card-header">قائمة الإجازات</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>الموظف</th>
                        <th>النوع</th>
                        <th>البداية</th>
                        <th>النهاية</th>
                        <th>الأيام</th>
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
                                <button class="btn btn-warning btn-sm" wire:click="edit({{ $leave->id }})">تعديل</button>
                                <button class="btn btn-danger btn-sm" wire:click="delete({{ $leave->id }})">حذف</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
