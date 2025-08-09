<div class="container mt-4" dir="rtl">
    <div class="card border-0 shadow">
        <div class="card-header bg-light text-white py-3">
            <h4 class="mb-0">
                <i class="mdi mdi-account-edit me-2"></i>
                تعديل بيانات العميل
            </h4>
        </div>

        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    <i class="mdi mdi-check-circle-outline me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form wire:submit.prevent="update" class="row g-3">
                <!-- الصف الأول -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">اسم العميل</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="mdi mdi-account-outline"></i></span>
                        <input type="text" class="form-control @error('form.name') is-invalid @enderror" 
                               wire:model.defer="form.name" placeholder="أدخل الاسم الكامل">
                    </div>
                    @error('form.name') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">البريد الإلكتروني</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="mdi mdi-email-outline"></i></span>
                        <input type="email" class="form-control @error('form.email') is-invalid @enderror" 
                               wire:model.defer="form.email" placeholder="example@domain.com">
                    </div>
                    @error('form.email') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- الصف الثاني -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">رقم الهاتف</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="mdi mdi-phone-outline"></i></span>
                        <input type="text" class="form-control @error('form.phone') is-invalid @enderror" 
                               wire:model.defer="form.phone" placeholder="05XXXXXXXX">
                    </div>
                    @error('form.phone') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">الحالة</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="mdi mdi-account-cog-outline"></i></span>
                        <select class="form-select @error('form.status') is-invalid @enderror" 
                                wire:model.defer="form.status">
                            <option value="" disabled selected>اختر حالة العميل</option>
                            <option value="new">جديد</option>
                            <option value="in_progress">جارٍ التنفيذ</option>
                            <option value="closed">مغلق</option>
                        </select>
                    </div>
                    @error('form.status') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- الصف الثالث -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">العنوان</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="mdi mdi-map-marker-outline"></i></span>
                        <input type="text" class="form-control @error('form.address') is-invalid @enderror" 
                               wire:model.defer="form.address" placeholder="الحي، الشارع، المدينة">
                    </div>
                    @error('form.address') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">الدولة</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="mdi mdi-earth"></i></span>
                        <input type="text" class="form-control @error('form.country') is-invalid @enderror" 
                               wire:model.defer="form.country" placeholder="اسم الدولة">
                    </div>
                    @error('form.country') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- زر التحديث -->
                <div class="col-12 mt-4">
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="update">
                                <i class="mdi mdi-content-save-edit-outline me-2"></i> تحديث البيانات
                            </span>
                            <span wire:loading wire:target="update">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                جاري التحديث...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="updateToast" class="toast align-items-center text-white bg-success" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="mdi mdi-check-circle-outline me-2"></i>
                تم تحديث بيانات العميل بنجاح
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="إغلاق"></button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    window.addEventListener('clientUpdated', () => {
        const toast = new bootstrap.Toast(document.getElementById('updateToast'));
        toast.show();
    });
});
</script>