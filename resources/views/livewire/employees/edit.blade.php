<div class="container-fluid py-4">
    <!-- رسالة النجاح -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- رسالة الخطأ العامة -->
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-user-edit me-2"></i> تحديث بيانات الموظف
                </h5>
                <a href="{{ route('employees.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> رجوع
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <form wire:submit.prevent="update" class="needs-validation" novalidate>
                <!-- الصف الأول -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">الاسم الكامل <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                            <input type="text" wire:model="full_name" class="form-control" required>
                        </div>
                        @error('full_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small">كود الموظف</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-id-card"></i></span>
                            <input type="text" wire:model="employee_code" class="form-control">
                        </div>
                        @error('employee_code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- الصف الثاني -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">البريد الإلكتروني</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                            <input type="email" wire:model="email" class="form-control">
                        </div>
                        @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small">رقم الهاتف</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                            <input type="text" wire:model="phone" class="form-control">
                        </div>
                        @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- الصف الثالث -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">المسمى الوظيفي</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-briefcase"></i></span>
                            <input type="text" wire:model="job_title" class="form-control">
                        </div>
                        @error('job_title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small">القسم</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-building"></i></span>
                            <input type="text" wire:model="department" class="form-control">
                        </div>
                        @error('department') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- الصف الرابع -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">حالة التوظيف</label>
                        <select wire:model="employment_status" class="form-select">
                            <option value="">اختر الحالة...</option>
                            <option value="دائم">دائم</option>
                            <option value="متعاقد">متعاقد</option>
                            <option value="تحت التدريب">تحت التدريب</option>
                        </select>
                        @error('employment_status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small">نوع التوظيف</label>
                        <select wire:model="employment_type" class="form-select">
                            <option value="">اختر النوع...</option>
                            <option value="دوام كامل">دوام كامل</option>
                            <option value="دوام جزئي">دوام جزئي</option>
                        </select>
                        @error('employment_type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- الصف الخامس -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">الراتب</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-money-bill-wave"></i></span>
                            <input type="number" wire:model="salary" class="form-control">
                            <span class="input-group-text bg-light">.ج.م</span>
                        </div>
                        @error('salary') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small">تاريخ التعيين</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" wire:model="hiring_date" class="form-control">
                            
                        </div>
                        @error('hiring_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- الصف السادس -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">تاريخ الميلاد</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-birthday-cake"></i></span>
                            <input type="date" wire:model="birth_date" class="form-control">
                        </div>
                        @error('birth_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small">النوع</label>
                        <select wire:model="gender" class="form-select">
                            <option value="">اختر النوع...</option>
                            <option value="ذكر">ذكر</option>
                            <option value="أنثى">أنثى</option>
                        </select>
                        @error('gender') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- العنوان -->
                <div class="mb-4">
                    <label class="form-label text-muted small">العنوان</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" wire:model="address" class="form-control">
                    </div>
                    @error('address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- الملاحظات -->
                <div class="mb-4">
                    <label class="form-label text-muted small">ملاحظات</label>
                    <textarea wire:model="notes" class="form-control" rows="3" placeholder="أي ملاحظات إضافية..."></textarea>
                    @error('notes') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- الحالة -->
                <div class="mb-4">
                    <label class="form-label text-muted small">حالة الموظف</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" wire:model="status" id="activeStatus" value="مفعل">
                            <label class="form-check-label" for="activeStatus">
                                <span class="badge bg-success">مفعل</span>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" wire:model="status" id="inactiveStatus" value="غير مفعل">
                            <label class="form-check-label" for="inactiveStatus">
                                <span class="badge bg-danger">غير مفعل</span>
                            </label>
                        </div>
                    </div>
                    @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- أزرار الإجراء -->
                <div class="d-flex justify-content-between border-top pt-4">
                    <button type="button" class="btn btn-outline-secondary" wire:click="resetForm">
                        <i class="fas fa-undo me-1"></i> إعادة تعيين
                    </button>
                    <div>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-save me-1"></i> حفظ التغييرات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
