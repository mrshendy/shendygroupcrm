<div class="project-form-container">
    <!-- Header Section -->
    <div class="form-header mb-5 text-center">
        <div class="header-icon">
            <i class="mdi mdi-rocket-launch-outline"></i>
        </div>
        <h1 class="form-title gradient-text">
            تعديل المشروع
        </h1>
        <p class="header-subtitle">
            قم بتحديث تفاصيل مشروعك
        </p>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-custom fade show">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-check-circle-outline alert-icon"></i>
                <div>
                    <h6 class="alert-heading">تم بنجاح!</h6>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Form Card -->
    <div class="card form-card">
        <div class="card-body p-5">
            <form wire:submit.prevent="update">
                <!-- Basic Information Section -->
                <div class="section-header">
                    <i class="mdi mdi-information-outline section-icon"></i>
                    <h3 class="section-title">المعلومات الأساسية</h3>
                </div>
                
                <div class="row g-4 mb-4">
                    <!-- Project Name -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="mdi mdi-form-textbox me-2"></i>
                            اسم المشروع <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-custom">
                            <span class="input-group-text"><i class="mdi mdi-text-box-outline"></i></span>
                            <input type="text" wire:model.defer="name" class="form-control" placeholder="اسم المشروع">
                        </div>
                        @error('name') 
                            <div class="error-message">
                                <i class="mdi mdi-alert-circle-outline"></i>{{ $message }}
                            </div> 
                        @enderror
                    </div>
                    
                    <!-- Project Type -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="mdi mdi-briefcase-outline me-2"></i>
                            نوع المشروع
                        </label>
                        <select wire:model="project_type" class="form-select select-custom">
                            <option value="">اختر النوع</option>
                            <option value="marketing">تسويق</option>
                            <option value="programming">برمجة</option>
                            <option value="crm">خدمة عملاء</option>
                            <option value="design">تصميم</option>
                            <option value="management">إدارة</option>
                            <option value="training">تدريب</option>
                        </select>
                        @error('project_type')
                            <div class="error-message">
                                <i class="mdi mdi-alert-circle-outline"></i>{{ $message }}
                            </div> 
                        @enderror
                    </div>
                    
                    <!-- Country -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="mdi mdi-map-marker-outline me-2"></i>
                            الدولة
                        </label>
                        <select wire:model.defer="country_id" class="form-select select-custom">
                            <option value="">اختر الدولة</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <div class="error-message">
                                <i class="mdi mdi-alert-circle-outline"></i>{{ $message }}
                            </div> 
                        @enderror
                    </div>
                    
                    <!-- Client -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="mdi mdi-account-box-outline me-2"></i>
                            العميل
                        </label>
                        <select wire:model.defer="client_id" class="form-select select-custom">
                            <option value="">اختر العميل</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Programming Type (Conditional) -->
                @if ($project_type == 'programming')
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="mdi mdi-code-braces me-2"></i>
                            نوع البرمجة
                        </label>
                        <select wire:model.defer="programming_type" class="form-select select-custom">
                            <option value="">اختر النوع</option>
                            <option value="web">موقع إلكتروني</option>
                            <option value="system">نظام داخلي</option>
                            <option value="mobile">تطبيق موبايل</option>
                            <option value="api">API</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="mdi mdi-progress-wrench me-2"></i>
                            المرحلة الحالية
                        </label>
                        <select wire:model.defer="phase" class="form-select select-custom">
                            <option value="">اختر المرحلة</option>
                            <option value="analysis">تحليل</option>
                            <option value="design">تصميم</option>
                            <option value="development">تطوير</option>
                            <option value="testing">اختبار</option>
                            <option value="deployment">تسليم</option>
                        </select>
                    </div>
                </div>
                @endif
                
                <!-- Description Section -->
                <div class="section-header mt-5">
                    <i class="mdi mdi-text-box-multiple-outline section-icon"></i>
                    <h3 class="section-title">الوصف التفصيلي</h3>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">
                        <i class="mdi mdi-text-long me-2"></i>
                        الوصف المختصر
                    </label>
                    <textarea wire:model.defer="description" class="form-control textarea-custom" rows="3" placeholder="وصف مختصر للمشروع..."></textarea>
                    @error('description') 
                        <div class="error-message">
                            <i class="mdi mdi-alert-circle-outline"></i>{{ $message }}
                        </div> 
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="form-label">
                        <i class="mdi mdi-file-document-outline me-2"></i>
                        التفاصيل الكاملة
                    </label>
                    <textarea wire:model.defer="details" class="form-control textarea-custom" rows="5" placeholder="اكتب التفاصيل الكاملة للمشروع..."></textarea>
                </div>
                
                <!-- Timeline Section -->
                <div class="section-header mt-5">
                    <i class="mdi mdi-calendar-clock section-icon"></i>
                    <h3 class="section-title">الجدول الزمني</h3>
                </div>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="mdi mdi-calendar-start me-2"></i>
                            تاريخ البدء
                        </label>
                        <div class="input-group input-group-custom">
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            <input type="date" wire:model.defer="start_date" class="form-control">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="mdi mdi-calendar-end me-2"></i>
                            تاريخ الانتهاء
                        </label>
                        <div class="input-group input-group-custom">
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            <input type="date" wire:model.defer="end_date" class="form-control">
                        </div>
                        @if ($showDeadlineAlert)
                            <div class="alert alert-warning alert-sm mt-2">
                                <i class="mdi mdi-alert-outline"></i> يقترب موعد انتهاء المشروع!
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Status Section -->
                <div class="section-header mt-5">
                    <i class="mdi mdi-state-machine section-icon"></i>
                    <h3 class="section-title">حالة المشروع</h3>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="mdi mdi-progress-check me-2"></i>
                            الحالة
                        </label>
                        <select wire:model.defer="status" class="form-select select-custom">
                            <option value="new">جديد</option>
                            <option value="in_progress">جاري العمل</option>
                            <option value="completed">مكتمل</option>
                            <option value="closed">مغلق</option>
                        </select>
                    </div>
                    
                    <!-- Priority -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="mdi mdi-priority-high me-2"></i>
                            الأولوية
                        </label>
                        <select wire:model.defer="priority" class="form-select select-custom">
                            <option value="low">منخفضة</option>
                            <option value="medium">متوسطة</option>
                            <option value="high">عالية</option>
                            <option value="critical">حرجة</option>
                        </select>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="form-actions mt-5 pt-4 border-top">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-action">
                            <i class="mdi mdi-arrow-left-thick me-2"></i> رجوع
                        </a>
                        
                        <div>
                            <button type="reset" class="btn btn-outline-danger btn-action me-3">
                                <i class="mdi mdi-autorenew me-2"></i> إعادة تعيين
                            </button>
                            <button type="submit" class="btn btn-primary btn-action">
                                <i class="mdi mdi-content-save-outline me-2"></i> تحديث المشروع
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Main Container */
    .project-form-container {
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    
    /* Header Styles */
    .form-header {
        position: relative;
        margin-bottom: 3rem;
    }
    
    .header-icon {
        font-size: 4rem;
        color: #3a7bd5;
        margin-bottom: 1.5rem;
        opacity: 0.8;
    }
    
    .form-title {
        font-weight: 800;
        margin-bottom: 0.5rem;
        font-size: 2.2rem;
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #3a7bd5, #00d2ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .header-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }
    
    /* Card Styles */
    .form-card {
        border-radius: 1rem;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    
    /* Section Headers */
    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .section-icon {
        font-size: 1.5rem;
        color: #3a7bd5;
        margin-left: 0.75rem;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0;
    }
    
    /* Form Elements */
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }
    
    .input-group-custom {
        margin-bottom: 0.5rem;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }
    
    .form-control, .textarea-custom {
        border-radius: 0.75rem;
        border-left: none;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
    }
    
    .form-control:focus, .textarea-custom:focus {
        box-shadow: 0 0 0 0.25rem rgba(58, 123, 213, 0.15);
        border-color: #3a7bd5;
    }
    
    .select-custom {
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%233a7bd5' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-position: left 0.75rem center;
        background-size: 16px 12px;
    }
    
    /* Buttons */
    .btn-action {
        padding: 0.75rem 1.75rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-primary {
        background-color: #3a7bd5;
        border: none;
        box-shadow: 0 4px 15px rgba(58, 123, 213, 0.3);
    }
    
    .btn-primary:hover {
        background-color: #2c5fb3;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(58, 123, 213, 0.4);
    }
    
    .btn-outline-secondary {
        border: 1px solid #dee2e6;
    }
    
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .btn-outline-danger {
        border: 1px solid #f8d7da;
    }
    
    /* Alerts */
    .alert-custom {
        border-radius: 0.75rem;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
    }
    
    .alert-icon {
        font-size: 1.5rem;
        margin-left: 0.75rem;
    }
    
    .alert-heading {
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    
    .alert-warning {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }
    
    .alert-sm {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        border-radius: 0.5rem;
    }
    
    /* Error Messages */
    .error-message {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .form-title {
            font-size: 1.75rem;
        }
        
        .header-icon {
            font-size: 3rem;
        }
        
        .form-actions {
            flex-direction: column;
            gap: 1rem;
        }
        
        .form-actions > div {
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
    }
</style>