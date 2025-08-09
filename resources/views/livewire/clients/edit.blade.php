<div class="container mt-4">
    <h4 class="mb-3">تعديل عميل</h4>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="update" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">اسم العميل</label>
            <input type="text" class="form-control @error('form.name') is-invalid @enderror"
                   wire:model.defer="form.name">
            @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">البريد الإلكتروني</label>
            <input type="email" class="form-control @error('form.email') is-invalid @enderror"
                   wire:model.defer="form.email">
            @error('form.email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">رقم الهاتف</label>
            <input type="text" class="form-control @error('form.phone') is-invalid @enderror"
                   wire:model.defer="form.phone">
            @error('form.phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">الحالة</label>
            <select class="form-select @error('form.status') is-invalid @enderror"
                    wire:model.defer="form.status">
                <option value="new">جديد</option>
                <option value="in_progress">جارٍ التنفيذ</option>
                <option value="closed">مغلق</option>
            </select>
            @error('form.status') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">العنوان</label>
            <input type="text" class="form-control @error('form.address') is-invalid @enderror"
                   wire:model.defer="form.address">
            @error('form.address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">الدولة</label>
            <input type="text" class="form-control @error('form.country') is-invalid @enderror"
                   wire:model.defer="form.country">
            @error('form.country') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary">
                <i class="mdi mdi-content-save-outline me-1"></i> تحديث
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.addEventListener('clientUpdated', () => {
                alert('تم تحديث بيانات العميل بنجاح!');
            });
        });
    </script>
</div>
