<div class="container mt-4">
    <h3 class="mb-4">تعديل الحضور والانصراف</h3>

    {{-- رسالة نجاح --}}
    @if (session()->has('success'))
        <div class="alert alert-success d-flex align-items-center">
            <i class="mdi mdi-check-circle-outline me-2 fs-5"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- الفورم --}}
    <form wire:submit.prevent="save">
        {{-- اختيار الموظف --}}
        <div class="mb-3">
            <label class="form-label">الموظف</label>
            <select class="form-select" wire:model="employee_id">
                <option value="">-- اختر موظف --</option>
                @foreach ($employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                @endforeach
            </select>
            @error('employee_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        {{-- وقت الحضور --}}
        <div class="mb-3">
            <label class="form-label">وقت الحضور</label>
            <input type="time" class="form-control" wire:model="check_in">
            @error('check_in') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        {{-- وقت الانصراف --}}
        <div class="mb-3">
            <label class="form-label">وقت الانصراف</label>
            <input type="time" class="form-control" wire:model="check_out">
            @error('check_out') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        {{-- عدد الساعات --}}
        <div class="mb-3">
            <label class="form-label">عدد الساعات</label>
            <input type="number" step="0.01" class="form-control" wire:model="hours" readonly>
            @error('hours') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        {{-- التاريخ --}}
        <div class="mb-3">
            <label class="form-label">التاريخ</label>
            <input type="date" class="form-control" wire:model="date">
            @error('date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
            <a href="{{ route('attendance.manage') }}" class="btn btn-secondary">رجوع</a>
        </div>
    </form>
</div>
