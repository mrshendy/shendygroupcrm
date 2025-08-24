<div class="container py-4">
    <h3 class="mb-4">
        <i class="mdi mdi-pencil-outline text-primary"></i>
        تعديل الحساب المالي
    </h3>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm">
            <i class="mdi mdi-check-circle-outline me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form wire:submit.prevent="update" class="card shadow-sm p-4">
        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label fw-semibold">اسم الحساب <span class="text-danger">*</span></label>
                <input type="text" wire:model.defer="name" class="form-control @error('name') is-invalid @enderror">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">رقم الحساب</label>
                <input type="text" wire:model.defer="account_number" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">نوع الحساب</label>
                <select wire:model.defer="type" class="form-select @error('type') is-invalid @enderror">
                    <option value="">اختر النوع...</option>
                    <option value="bank">بنكي</option>
                    <option value="cash">نقدي</option>
                    <option value="wallet">محفظة</option>
                    <option value="investment">استثمار</option>
                    <option value="instapay">انستا باي</option>
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">الرصيد الافتتاحي</label>
                <input type="number" wire:model.defer="opening_balance" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">اسم البنك</label>
                <input type="text" wire:model.defer="bank" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">ملاحظات</label>
                <textarea wire:model.defer="notes" rows="3" class="form-control"></textarea>
            </div>

            <div class="col-md-6">
                <div class="form-check">
                    <input type="checkbox" wire:model.defer="is_main" id="is_main" class="form-check-input">
                    <label class="form-check-label" for="is_main">حساب رئيسي</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-check">
                    <input type="checkbox" wire:model.defer="status" id="status" class="form-check-input">
                    <label class="form-check-label" for="status">نشط</label>
                </div>
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-success px-4">
                    <i class="mdi mdi-content-save-edit-outline me-1"></i>
                    تحديث الحساب
                </button>
                <a href="{{ route('finance.accounts.manage') }}" class="btn btn-secondary px-4">رجوع</a>
            </div>

        </div>
    </form>
</div>
