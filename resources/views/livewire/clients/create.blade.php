<div class="py-4">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="mdi mdi-check-circle-outline me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form wire:submit.prevent="save">


        @foreach ($clients as $index => $client)
            <div class="card shadow-sm mb-4 border-primary" wire:key="client-{{ $index }}">


                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="mdi mdi-account-{{ $client['type'] === 'full' ? 'edit' : 'tie' }} me-2"></i>
                        {{ $client['type'] === 'full' ? 'إضافة عميل جديد' : 'مسؤول تواصل' }}
                    </h5>
                    @if ($index > 0)
                        <button type="button" class="btn btn-sm btn-danger d-flex align-items-center"
                            onclick="confirm('هل أنت متأكد من حذف هذا العميل؟') || event.stopImmediatePropagation()"
                            wire:click="removeClient({{ $index }})">
                            <i class="mdi mdi-delete-outline me-1"></i> حذف
                        </button>
                    @endif
                </div>

                <div class="card-body">

                    @if ($client['type'] === 'full')
                        <div class="mb-4">
                            <h6 class="text-muted mb-3 d-flex align-items-center">
                                <i class="mdi mdi-card-account-details-outline me-2"></i> البيانات الأساسية
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">الاسم <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-account-outline"></i></span>
                                        <input type="text" class="form-control"
                                            wire:model.lazy="clients.{{ $index }}.name" required>
                                    </div>
                                    @error("clients.$index.name")
                                        <div class="invalid-feedback d-block"><i
                                                class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">البريد الإلكتروني</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-email-outline"></i></span>
                                        <input type="email" class="form-control"
                                            wire:model.lazy="clients.{{ $index }}.email">
                                    </div>
                                    @error("clients.$index.email")
                                        <div class="invalid-feedback d-block"><i
                                                class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">رقم الهاتف</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-phone-outline"></i></span>
                                        <input type="tel" class="form-control"
                                            wire:model.lazy="clients.{{ $index }}.phone">
                                    </div>
                                    @error("clients.$index.phone")
                                        <div class="invalid-feedback d-block"><i
                                                class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">الحالة</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i
                                                class="mdi mdi-information-outline"></i></span>
                                        <select wire:model="clients.{{ $index }}.status" class="form-select">
                                            <option value="new">جديد</option>
                                            <option value="in_progress">قيد التنفيذ</option>
                                            <option value="closed">موقوف</option>
                                        </select>
                                    </div>
                                    @error("clients.$index.status")
                                        <div class="invalid-feedback d-block"><i
                                                class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ✅ بيانات العنوان --}}
                        <div class="mb-4">
                            <h6 class="text-muted mb-3 d-flex align-items-center">
                                <i class="mdi mdi-map-marker-outline me-2"></i> بيانات العنوان
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">العنوان</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-home-outline"></i></span>
                                        <input type="text" class="form-control"
                                            wire:model.lazy="clients.{{ $index }}.address">
                                    </div>
                                    @error("clients.$index.address")
                                        <div class="invalid-feedback d-block"><i
                                                class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">الدولة</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-earth"></i></span>
                                        <select class="form-select" wire:model="clients.{{ $index }}.country">
                                            <option value="">اختر الدولة</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country }}">{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error("clients.$index.country")
                                        <div class="invalid-feedback d-block"><i
                                                class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif



                    {{-- ✅ بيانات التواصل --}}
                    <div class="mb-0">
                        <h6 class="text-muted mb-3 d-flex align-items-center">
                            <i class="mdi mdi-account-tie-outline me-2"></i> شخص التواصل
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">اسم المسؤول</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="mdi mdi-account-tie"></i></span>
                                    <input type="text" class="form-control"
                                        wire:model.lazy="clients.{{ $index }}.contact_name">
                                </div>
                                @error("clients.$index.contact_name")
                                    <div class="invalid-feedback d-block"><i
                                            class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">الوظيفة</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="mdi mdi-briefcase-outline"></i></span>
                                    <input type="text" class="form-control"
                                        wire:model.lazy="clients.{{ $index }}.contact_job">
                                </div>
                                @error("clients.$index.contact_job")
                                    <div class="invalid-feedback d-block"><i
                                            class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">هاتف التواصل</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="mdi mdi-cellphone"></i></span>
                                    <input type="tel" class="form-control"
                                        wire:model.lazy="clients.{{ $index }}.contact_phone">
                                </div>
                                @error("clients.$index.contact_phone")
                                    <div class="invalid-feedback d-block"><i
                                            class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">بريد التواصل</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="mdi mdi-at"></i></span>
                                    <input type="email" class="form-control"
                                        wire:model.lazy="clients.{{ $index }}.contact_email">
                                </div>
                                @error("clients.$index.contact_email")
                                    <div class="invalid-feedback d-block"><i
                                            class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch ps-0">
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input ms-0 me-2" type="checkbox"
                                            wire:model="clients.{{ $index }}.is_primary"
                                            id="primaryContact{{ $index }}">
                                        <label class="form-check-label fw-bold d-flex align-items-center"
                                            for="primaryContact{{ $index }}">
                                            <i class="mdi mdi-star-outline me-1 text-warning"></i> المسؤول الرئيسي
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> {{-- نهاية card-body --}}
            </div> {{-- نهاية card --}}
        @endforeach

        {{-- ✅ أزرار التحكم --}}
        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-success d-flex align-items-center" wire:click="addClient">
                <i class="mdi mdi-plus-circle-outline me-2"></i> إضافة مسؤول تواصل
            </button>

            <div class="d-flex gap-2">
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                    <i class="mdi mdi-arrow-left me-2"></i> رجوع
                </a>
                <button type="submit" class="btn btn-primary d-flex align-items-center">
                    <i class="mdi mdi-content-save-outline me-2"></i> حفظ العميل
                </button>
            </div>
        </div>
    </form>
</div>
