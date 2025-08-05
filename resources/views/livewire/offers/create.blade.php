<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-light text-white rounded-top-4 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-semibold">
                    <i class="mdi mdi-file-document-edit-outline me-2"></i>إنشاء عرض جديد
                </h4>
               
            </div>
        </div>

        <div class="card-body p-4">
            @if (session()->has('success'))
                <div class="alert alert-success d-flex align-items-center mb-4 rounded-3">
                    <i class="mdi mdi-check-circle-outline me-2 fs-3"></i>
                    <div class="fw-medium">{{ session('success') }}</div>
                </div>
            @endif

            <form wire:submit.prevent="store" enctype="multipart/form-data" class="needs-validation" novalidate>
                <!-- العميل -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">
                        <i class="mdi mdi-account-tie-outline me-1 text-primary"></i>العميل
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group border rounded-3 overflow-hidden">
                        <span class="input-group-text bg-light border-0">
                            <i class="mdi mdi-account-search text-muted"></i>
                        </span>
                        <select wire:model="client_id" class="form-select border-0 py-3" required>
                            <option value="">-- اختر العميل --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('client_id') 
                        <div class="text-danger small mt-1 d-flex align-items-center">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </div> 
                    @enderror
                </div>

                <!-- المشاريع -->
                @if(!empty($projects))
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="mdi mdi-office-building-outline me-1 text-primary"></i>المشروع
                        </label>
                        <div class="input-group border rounded-3 overflow-hidden">
                            <span class="input-group-text bg-light border-0">
                                <i class="mdi mdi-home-search text-muted"></i>
                            </span>
                            <select wire:model="project_id" class="form-select border-0 py-3">
                                <option value="">-- اختر المشروع (اختياري) --</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('project_id') 
                            <div class="text-danger small mt-1 d-flex align-items-center">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div> 
                        @enderror
                    </div>
                @endif

                <!-- التواريخ -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">
                            <i class="mdi mdi-calendar-arrow-right me-1 text-primary"></i>تاريخ البداية
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group border rounded-3 overflow-hidden">
                            <span class="input-group-text bg-light border-0">
                                <i class="mdi mdi-calendar-blank text-muted"></i>
                            </span>
                            <input type="date" wire:model="start_date" class="form-control border-0 py-3" required>
                        </div>
                        @error('start_date') 
                            <div class="text-danger small mt-1 d-flex align-items-center">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div> 
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">
                            <i class="mdi mdi-calendar-arrow-left me-1 text-primary"></i>تاريخ النهاية
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group border rounded-3 overflow-hidden">
                            <span class="input-group-text bg-light border-0">
                                <i class="mdi mdi-calendar-blank text-muted"></i>
                            </span>
                            <input type="date" wire:model="end_date" class="form-control border-0 py-3" required>
                        </div>
                        @error('end_date') 
                            <div class="text-danger small mt-1 d-flex align-items-center">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div> 
                        @enderror
                    </div>
                </div>

                <!-- حالة العرض -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">
                        <i class="mdi mdi-information-outline me-1 text-primary"></i>حالة العرض
                    </label>
                    <div class="input-group border rounded-3 overflow-hidden">
                        <span class="input-group-text bg-light border-0">
                            <i class="mdi mdi-state-machine text-muted"></i>
                        </span>
                        <select wire:model="status" class="form-select border-0 py-3">
                            <option value="active">نشط</option>
                <option value="closed">مغلق</option>
                <option value="expired">منتهي</option>
                        </select>
                    </div>
                    @error('status') 
                        <div class="text-danger small mt-1 d-flex align-items-center">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </div> 
                    @enderror
                </div>

                <!-- التفاصيل -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">
                        <i class="mdi mdi-text-box-search-outline me-1 text-primary"></i>تفاصيل العرض
                        <span class="text-danger">*</span> 
                    </label>
                    <div class="border rounded-3 overflow-hidden">
                        <textarea wire:model="details" class="form-control border-0 p-3" rows="5" placeholder="أدخل تفاصيل العرض هنا..." required></textarea>
                    </div>
                    @error('details') 
                        <div class="text-danger small mt-1 d-flex align-items-center">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </div> 
                    @enderror
                </div>

                <!-- المبلغ -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">
                        <i class="mdi mdi-cash-plus me-1 text-primary"></i>قيمة العرض
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group border rounded-3 overflow-hidden">
                        <span class="input-group-text bg-light border-0">
                            <i class="mdi mdi-currency-usd text-muted"></i>
                        </span>
                        <input type="number" wire:model="amount" class="form-control border-0 py-3" step="0.01" placeholder="0.00" required>
                        <span class="input-group-text bg-light border-0">ج.م</span>
                    </div>
                    @error('amount') 
                        <div class="text-danger small mt-1 d-flex align-items-center">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </div> 
                    @enderror
                </div>

                <!-- شامل الضريبة -->
                <div class="mb-4">
                    <div class="form-check form-switch ps-0">
                        <input type="checkbox" wire:model="include_tax" class="form-check-input ms-0 me-2" id="includeTax" style="width: 3rem; height: 1.5rem;">
                        <label class="form-check-label fw-semibold text-dark" for="includeTax">
                            <i class="mdi mdi-receipt-text-check me-1 text-primary"></i>شامل الضريبة
                        </label>
                    </div>
                </div>

                <!-- وصف -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">
                        <i class="mdi mdi-note-text me-1 text-primary"></i>الوصف الإضافي
                    </label>
                    <div class="border rounded-3 overflow-hidden">
                        <textarea wire:model="description" class="form-control border-0 p-3" rows="3" placeholder="أي ملاحظات إضافية..."></textarea>
                    </div>
                    @error('description') 
                        <div class="text-danger small mt-1 d-flex align-items-center">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </div> 
                    @enderror
                </div>

                <!-- ملف -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">
                        <i class="mdi mdi-paperclip me-1 text-primary"></i>المرفقات
                    </label>
                    <div class="file-upload-wrapper">
                        <label for="attachment" class="d-block cursor-pointer">
                            <div class="border rounded-3 p-4 text-center bg-light-hover">
                                <i class="mdi mdi-cloud-upload-outline display-6 text-muted mb-2"></i>
                                <h5 class="fw-medium mb-1">اسحب وأسقط الملف هنا</h5>
                                <p class="text-muted small mb-2">أو انقر لاختيار الملف</p>
                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                    <i class="mdi mdi-folder-open-outline me-1"></i>تصفح الملفات
                                </span>
                            </div>
                        </label>
                        <input type="file" wire:model="attachment" id="attachment" class="d-none">
                        @error('attachment') 
                            <div class="text-danger small mt-1 d-flex align-items-center">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div> 
                        @enderror
                    </div>
                    
                    @if ($attachment)
                        <div class="alert alert-info rounded-3 mt-3 d-flex align-items-center">
                            <i class="mdi mdi-file-check-outline me-2 fs-3 text-primary"></i>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <strong class="me-2">{{ $attachment->getClientOriginalName() }}</strong>
                                    <button type="button" class="btn btn-sm btn-icon" wire:click="$set('attachment', null)">
                                        <i class="mdi mdi-close"></i>
                                    </button>
                                </div>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" 
                                         role="progressbar" style="width: 75%"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-between border-top pt-4 mt-4">
                    <a href="{{ route('offers.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="mdi mdi-arrow-left-thin me-1"></i> رجوع
                    </a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="mdi mdi-content-save-check-outline me-1"></i> حفظ العرض
                        <span wire:loading wire:target="store">
                            <i class="mdi mdi-loading mdi-spin ms-1"></i>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        overflow: hidden;
    }
    
    .form-control, .form-select {
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }
    
    .form-control:focus, .form-select:focus {
        box-shadow: none;
        border-color: #86b7fe;
    }
    
    .input-group-text {
        min-width: 45px;
        justify-content: center;
        background-color: #f8f9fa;
    }
    
    .file-upload-wrapper {
        position: relative;
        overflow: hidden;
    }
    
    .file-upload-wrapper:hover .bg-light-hover {
        background-color: #f1f3f5 !important;
        border-color: #adb5bd;
    }
    
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }
    
    .rounded-4 {
        border-radius: 1rem !important;
    }
    
    .rounded-top-4 {
        border-top-left-radius: 1rem !important;
        border-top-right-radius: 1rem !important;
    }
    
    .cursor-pointer {
        cursor: pointer;
    }
    
    .bg-light-hover {
        transition: all 0.2s ease;
    }
    
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }
    
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
    }
    
    .alert-info {
        background-color: #e7f5ff;
        border-color: #d0ebff;
    }
</style>