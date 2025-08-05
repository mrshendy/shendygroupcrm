<div class="container py-4">
    <!-- Header with icon -->
    <div class="d-flex align-items-center mb-4">
        <i class="mdi mdi-account-edit-outline fa-2x text-primary me-3"></i>
        <h4 class="mb-0">تعديل بيانات العميل</h4>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center">
            <i class="mdi mdi-check-circle-outline me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form wire:submit.prevent="updateClient">
        <div class="card shadow-sm mb-4 border-primary">
            <div class="card-header bg-light text-white">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="mdi mdi-information-outline me-2"></i>
                    المعلومات الأساسية
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- الاسم -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">الاسم <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-account-outline"></i></span>
                            <input type="text" wire:model.defer="client.name" class="form-control" >

                            
                        </div>
                        @error('client.name') 
                            <div class="invalid-feedback d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div> 
                        @enderror
                    </div>

                    <!-- البريد الإلكتروني -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">البريد الإلكتروني</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-email-outline"></i></span>
                            <input type="email" wire:model.defer="client.email" class="form-control">
                        </div>
                        @error('client.email') 
                            <div class="invalid-feedback d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div> 
                        @enderror
                    </div>

                    <!-- الهاتف -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">رقم الهاتف</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-phone-outline"></i></span>
                            <input type="text" wire:model.defer="client.phone" class="form-control">
                        </div>
                        @error('client.phone') 
                            <div class="invalid-feedback d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div> 
                        @enderror
                    </div>

                    <!-- الحالة -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">الحالة</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-list-status"></i></span>
                            <select wire:model.defer="client.status" class="form-select">
                                <option value="new">جديد</option>
                                <option value="in_progress">جاري العمل</option>
                                <option value="closed">ايقاف</option>
                            </select>
                        </div>
                        @error('client.status') 
                            <div class="invalid-feedback d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div> 
                        @enderror
                    </div>

                    <!-- العنوان -->
                    <div class="col-md-8">
                        <label class="form-label fw-bold">العنوان</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-home-outline"></i></span>
                            <input type="text" class="form-control" wire:model.defer="client.address">
                        </div>
                        @error('client.address')
                            <div class="invalid-feedback d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- الدولة -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">الدولة</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-earth"></i></span>
                            <select class="form-select" wire:model.defer="client.country">
                                <option value="">اختر الدولة</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country }}">{{ $country }}</option>
                                @endforeach
                            </select>

                            


                        </div>
                        @error('client.country')
                            <div class="invalid-feedback d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- بيانات التواصل -->
        <div class="card shadow-sm mb-4 border-primary">
            <div class="card-header bg-light">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="mdi mdi-account-tie-outline me-2"></i> بيانات التواصل
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">اسم المسؤول</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-account-tie"></i></span>
                            <input type="text" class="form-control" wire:model.defer="client.contact_name">
                        </div>
                        @error('client.contact_name')
                            <div class="invalid-feedback d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">الوظيفة</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-briefcase-outline"></i></span>
                            <input type="text" class="form-control" wire:model.defer="client.contact_job">
                        </div>
                        @error('client.contact_job')
                            <div class="invalid-feedback d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">هاتف التواصل</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-cellphone"></i></span>
                            <input type="tel" class="form-control" wire:model.defer="client.contact_phone">
                        </div>
                        @error('client.contact_phone')
                            <div class="invalid-feedback d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">بريد التواصل</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-at"></i></span>
                            <input type="email" class="form-control" wire:model.defer="client.contact_email">
                        </div>
                        @error('client.contact_email')
                            <div class="invalid-feedback d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch ps-0">
                            <div class="d-flex align-items-center">
                                <input class="form-check-input ms-0 me-2" type="checkbox" wire:model.defer="client.is_primary" id="primaryContact">
                                <label class="form-check-label fw-bold d-flex align-items-center" for="primaryContact">
                                    <i class="mdi mdi-star-outline me-1 text-warning"></i> المسؤول الرئيسي
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                <i class="mdi mdi-arrow-left me-2"></i> رجوع
            </a>
            <button type="submit" class="btn btn-primary d-flex align-items-center">
                <i class="mdi mdi-content-save-outline me-2"></i> تحديث البيانات
            </button>
        </div>
    </form>
</div>

<style>
    .card {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
    }
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    .input-group-text {
        min-width: 40px;
        justify-content: center;
        background-color: #f8f9fa;
    }
    .invalid-feedback {
        display: flex;
        align-items: center;
    }
    .mdi {
        font-size: 1.1em;
    }
</style>