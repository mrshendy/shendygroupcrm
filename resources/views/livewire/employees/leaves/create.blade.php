<div>
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label class="form-label">نوع الإجازة</label>
            <select wire:model="leave_type" class="form-control">
                <option value="annual">سنوية</option>
                <option value="sick">مرضية</option>
                <option value="unpaid">بدون راتب</option>
                <option value="other">أخرى</option>
            </select>
            @error('leave_type') <span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">تاريخ البداية</label>
            <input type="date" wire:model="start_date" class="form-control">
            @error('start_date') <span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">تاريخ النهاية</label>
            <input type="date" wire:model="end_date" class="form-control">
            @error('end_date') <span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">السبب (اختياري)</label>
            <textarea wire:model="reason" class="form-control"></textarea>
            @error('reason') <span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn btn-primary">إرسال الطلب</button>
    </form>
</div>
