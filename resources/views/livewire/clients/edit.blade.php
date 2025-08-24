<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-4">
        <h3 class="mb-0">تعديل بيانات العميل</h3>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-pill">{{ session('success') }}</div>
    @endif

    <div class="row g-3">
        {{-- الاسم --}}
        <div class="col-md-6">
            <label class="form-label">اسم العميل *</label>
            <div class="input-group">
                <span class="input-group-text"><i class="mdi mdi-account-outline"></i></span>
                <input type="text" class="form-control" wire:model.defer="name" placeholder="اسم العميل">
            </div>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- البريد الإلكتروني --}}
        <div class="col-md-6">
            <label class="form-label">البريد الإلكتروني</label>
            <div class="input-group">
                <span class="input-group-text"><i class="mdi mdi-email-outline"></i></span>
                <input type="email" class="form-control" wire:model.defer="email" placeholder="example@mail.com">
            </div>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- الهاتف --}}
        <div class="col-md-6">
            <label class="form-label">رقم الهاتف</label>
            <div class="input-group">
                <span class="input-group-text"><i class="mdi mdi-phone"></i></span>
                <input type="text" class="form-control" wire:model.defer="phone" placeholder="0123456789">
            </div>
            @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- الحالة --}}
        <div class="col-md-6">
            <label class="form-label">الحالة</label>
            <div class="input-group">
                <span class="input-group-text"><i class="mdi mdi-information-outline"></i></span>
                <select class="form-select" wire:model.defer="status">
                   <option value="new">جديد</option>
                                            <option value="in_progress">قيد التنفيذ</option>
                                            <option value="active">نشط</option>
                                            <option value="closed">مغلق</option>
                </select>
            </div>
            @error('status') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- العنوان --}}
        <div class="col-12">
            <label class="form-label">العنوان</label>
            <div class="input-group">
                <span class="input-group-text"><i class="mdi mdi-home-outline"></i></span>
                <input type="text" class="form-control" wire:model.defer="address" placeholder="العنوان">
            </div>
            @error('address') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- الدولة --}}
        <div class="col-md-6">
            <label class="form-label">الدولة</label>
            <div class="input-group">
                <span class="input-group-text"><i class="mdi mdi-earth"></i></span>
                <select class="form-select" wire:model.defer="country_id">
                    <option value="">اختر الدولة</option>
                    @foreach($countries as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            @error('country_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- اسم المسؤول --}}
        <div class="col-md-6">
            <label class="form-label">اسم المسؤول</label>
            <div class="input-group">
                <span class="input-group-text"><i class="mdi mdi-account-tie-outline"></i></span>
                <input type="text" class="form-control" wire:model.defer="contact_name" placeholder="اسم شخص التواصل">
            </div>
            @error('contact_name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- الوظيفة --}}
        <div class="col-md-6">
            <label class="form-label">الوظيفة</label>
            <div class="input-group">
                <span class="input-group-text"><i class="mdi mdi-briefcase-outline"></i></span>
                <input type="text" class="form-control" wire:model.defer="job" placeholder="المسمى الوظيفي">
            </div>
            @error('job') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- هاتف التواصل --}}
        <div class="col-md-6">
            <label class="form-label">هاتف التواصل</label>
            <div class="input-group">
                <span class="input-group-text"><i class="mdi mdi-cellphone"></i></span>
                <input type="text" class="form-control" wire:model.defer="contact_phone" placeholder="هاتف التواصل">
            </div>
            @error('contact_phone') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- بريد التواصل --}}
        <div class="col-md-6">
            <label class="form-label">بريد التواصل</label>
            <div class="input-group">
                <span class="input-group-text"><i class="mdi mdi-at"></i></span>
                <input type="email" class="form-control" wire:model.defer="contact_email" placeholder="contact@mail.com">
            </div>
            @error('contact_email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- المسؤول الرئيسي --}}
        <div class="col-md-6 d-flex align-items-center">
            <div class="form-check form-switch mt-4">
                <input class="form-check-input" type="checkbox" role="switch" id="mainContactSwitch" wire:model.defer="is_main_contact">
                <label class="form-check-label" for="mainContactSwitch">المسؤول الرئيسي</label>
            </div>
            @error('is_main_contact') <small class="text-danger ms-2">{{ $message }}</small> @enderror
        </div>
    </div>

    <div class="mt-4">
        <button class="btn btn-primary rounded-pill" wire:click="update">
            <i class="mdi mdi-content-save-outline me-1"></i> تحديث البيانات
        </button>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('clientUpdated', () => {
        // Toast أو SweetAlert
        console.log('Client updated');
    });
</script>
@endpush
