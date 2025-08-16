<div>
    <!-- رسائل الفلاش -->
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- نموذج تقديم الإجازة -->
    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label for="leave_type" class="form-label">نوع الإجازة</label>
            <select wire:model="leave_type" id="leave_type" class="form-control">
                <option value="annual">سنوية</option>
                <option value="casual">عارضة</option>
                <option value="sick">مرضية</option>
                <option value="unpaid">بدون راتب</option>
                <option value="other">أخرى</option>
            </select>
            @error('leave_type') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">تاريخ البداية</label>
            <input type="date" wire:model="start_date" id="start_date" class="form-control">
            @error('start_date') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">تاريخ النهاية</label>
            <input type="date" wire:model="end_date" id="end_date" class="form-control">
            @error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="reason" class="form-label">السبب (اختياري)</label>
            <textarea wire:model="reason" id="reason" rows="3" class="form-control"></textarea>
            @error('reason') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="mdi mdi-check-circle-outline"></i> تقديم الطلب
        </button>
    </form>

    <!-- جدول آخر الطلبات -->
    <hr>
    <h5 class="mt-4">طلبات الإجازة السابقة</h5>
    <table class="table table-bordered table-striped mt-2">
        <thead>
            <tr>
                <th>النوع</th>
                <th>من</th>
                <th>إلى</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @php
                $employeeId = Auth::user()->employee_id ?? null;
                $leaves = \App\Models\Leave::where('employee_id', $employeeId)
                            ->orderBy('start_date','desc')->limit(5)->get();
            @endphp

            @forelse ($leaves as $leave)
                <tr>
                    <td>
                        @switch($leave->leave_type)
                            @case('annual') سنوية @break
                            @case('casual') عارضة @break
                            @case('sick') مرضية @break
                            @case('unpaid') بدون راتب @break
                            @default أخرى
                        @endswitch
                    </td>
                    <td>{{ $leave->start_date }}</td>
                    <td>{{ $leave->end_date }}</td>
                    <td>
                        @if ($leave->status == 'pending')
                            <span class="badge bg-warning text-dark">قيد المراجعة</span>
                        @elseif ($leave->status == 'approved')
                            <span class="badge bg-success">موافق عليها</span>
                        @elseif ($leave->status == 'rejected')
                            <span class="badge bg-danger">مرفوضة</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">لا توجد طلبات إجازة سابقة</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
