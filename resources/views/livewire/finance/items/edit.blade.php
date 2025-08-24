<div class="container-fluid px-4 py-3">

    {{-- التنبيه --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="mdi mdi-check-circle-outline me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- كارد تعديل --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light fw-bold">
            <i class="mdi mdi-pencil-outline me-1"></i> تعديل البند
        </div>
        <div class="card-body">
            <form wire:submit.prevent="update" class="row g-3">

                {{-- اسم البند --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">اسم البند</label>
                    <input type="text" class="form-control shadow-sm" wire:model="name" placeholder="أدخل اسم البند">
                    @error('name') 
                        <small class="text-danger"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</small> 
                    @enderror
                </div>

                {{-- النوع --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold">النوع</label>
                    <select class="form-select shadow-sm" wire:model="type">
                        <option value="">اختر النوع...</option>
                        <option value="مصروف">مصروف</option>
                        <option value="إيراد">إيراد</option>
                    </select>
                    @error('type') 
                        <small class="text-danger"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</small> 
                    @enderror
                </div>

                {{-- الحالة --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold">الحالة</label>
                    <select class="form-select shadow-sm" wire:model="status">
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                    @error('status') 
                        <small class="text-danger"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</small> 
                    @enderror
                </div>

                {{-- زر الحفظ --}}
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="mdi mdi-content-save-edit-outline me-1"></i> تحديث
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
