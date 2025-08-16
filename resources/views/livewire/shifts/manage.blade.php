<div>
    <!-- فورم إضافة / تعديل شيفت -->
    <form wire:submit.prevent="save" class="mb-4">
        <div class="mb-3">
            <label class="form-label">اسم الشيفت</label>
            <input type="text" wire:model="name" class="form-control">
            @error('name') <span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">الأيام</label><br>
            @foreach(['saturday','sunday','monday','tuesday','wednesday','thursday','friday'] as $day)
                <label class="me-2">
                    <input type="checkbox" wire:model="days" value="{{ $day }}"> {{ $day }}
                </label>
            @endforeach
            @error('days') <span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">من</label>
            <input type="time" wire:model="start_time" class="form-control">
            @error('start_time') <span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">إلى</label>
            <input type="time" wire:model="end_time" class="form-control">
            @error('end_time') <span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">مدة السماح بالإجازات (أيام)</label>
            <input type="number" wire:model="leave_allowance" class="form-control">
            @error('leave_allowance') <span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn btn-success">
            {{ $shift_id ? 'تحديث' : 'حفظ' }}
        </button>
        @if($shift_id)
            <button type="button" wire:click="resetForm" class="btn btn-secondary">إلغاء التعديل</button>
        @endif
    </form>

    <!-- جدول الشيفتات -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>الأيام</th>
                <th>من</th>
                <th>إلى</th>
                <th>مدة الإجازات</th>
                <th>العمليات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shifts as $shift)
                <tr>
                    <td>{{ $shift->name }}</td>
                    <td>{{ implode(', ', $shift->days) }}</td>
                    <td>{{ $shift->start_time }}</td>
                    <td>{{ $shift->end_time }}</td>
                    <td>{{ $shift->leave_allowance }}</td>
                    <td>
                        <button wire:click="edit({{ $shift->id }})" class="btn btn-primary btn-sm">تعديل</button>
                        <button wire:click="delete({{ $shift->id }})" class="btn btn-danger btn-sm">حذف</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
