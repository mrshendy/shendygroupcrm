<div class="container" dir="rtl">
    {{-- تنبيهات --}}
    @if (session()->has('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    <div class="card mt-3 shadow-sm">
        <div class="card-header fw-bold">
            {{ $leave_id ? 'تعديل طلب إجازة' : 'تقديم طلب إجازة' }}
        </div>

        <div class="card-body row g-3">

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
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
                @error('leave_shift_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">نوع الإجازة</label>
                <select class="form-select" wire:model="leave_type">
                    <option value="annual">سنوية</option>
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
                {{-- الأخطاء تخص start_date (مش alias) --}}
                @error('start_date') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">إلى</label>
                <input type="date" class="form-control" wire:model="leave_end_date">
                @error('end_date') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label">سبب/ملاحظات</label>
                <textarea class="form-control" rows="2" wire:model="reason"></textarea>
                @error('reason') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 d-flex gap-2">
                <button type="button" class="btn btn-success" wire:click="saveLeave">
                    <i class="mdi mdi-content-save-outline me-1"></i> حفظ
                </button>
                @if($leave_id)
                    <a href="{{ request()->url() }}" class="btn btn-outline-secondary">بدء طلب جديد</a>
                @endif
            </div>

        </div>
    </div>
</div>
