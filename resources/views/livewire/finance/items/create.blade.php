@if (session()->has('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light text-white">
        <h5 class="mb-0">
            <i class="fas fa-plus-circle me-2"></i> إضافة بند جديد
        </h5>
    </div>

    <form wire:submit.prevent="save" class="needs-validation" novalidate>
        <div class="card-body">
            <div class="row g-3">
                <!-- اسم البند -->
                <div class="col-md-6">
                    <label for="name" class="form-label fw-semibold">اسم البند <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-tag"></i></span>
                        <input type="text" id="name" class="form-control shadow-sm" wire:model="name" required placeholder="أدخل اسم البند">
                    </div>
                    @error('name') 
                        <div class="invalid-feedback d-block mt-1 small">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                        </div> 
                    @enderror
                </div>

                <!-- النوع -->
                <div class="col-md-6">
                    <label for="type" class="form-label fw-semibold">النوع <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-list-alt"></i></span>
                        <select id="type" class="form-select shadow-sm" wire:model="type" required>
                            <option value="" selected disabled>اختر نوع البند...</option>
                            <option value="مصروف">مصروف</option>
                            <option value="إراد">إراد</option>
                
                        </select>
                    </div>
                    @error('type') 
                        <div class="invalid-feedback d-block mt-1 small">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                        </div> 
                    @enderror
                </div>

                <!-- الحالة -->
                <div class="col-md-6">
                    <label for="status" class="form-label fw-semibold">الحالة</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-power-off"></i></span>
                        <select id="status" class="form-select shadow-sm" wire:model="status">
                            <option value="active" class="text-success">نشط</option>
                            <option value="inactive" class="text-secondary">غير نشط</option>
                        </select>
                    </div>
                    @error('status') 
                        <div class="invalid-feedback d-block mt-1 small">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                        </div> 
                    @enderror
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end border-top-0">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i> حفظ البند
            </button>
        </div>
    </form>
</div>
