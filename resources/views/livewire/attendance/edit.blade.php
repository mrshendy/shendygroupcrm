<div class="container mt-4">
    <h3 class="mb-4">تعديل الحضور والانصراف</h3>

    @if(session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label class="form-label">كود الموظف</label>
            <input type="text" class="form-control" wire:model="employee_id">
            @error('employee_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">وقت الحضور</label>
            <input type="time" class="form-control" wire:model="check_in">
            @error('check_in') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">وقت الانصراف</label>
            <input type="time" class="form-control" wire:model="check_out">
            @error('check_out') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">عدد الساعات</label>
            <input type="number" step="0.01" class="form-control" wire:model="hours">
            @error('hours') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">التاريخ</label>
            <input type="date" class="form-control" wire:model="date">
            @error('date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button class="btn btn-primary">حفظ التعديلات</button>
    </form>
</div>
