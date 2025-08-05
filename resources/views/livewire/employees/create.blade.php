<div class="container-fluid py-4">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
            <span class="alert-text">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg">
        <div class="card-header bg-light text-white">
            <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>إضافة موظف جديد</h5>
        </div>
    
        <div class="card-body">
            <form wire:submit.prevent="save" class="needs-validation" novalidate>
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" wire:model="full_name" class="form-control" id="fullNameInput" placeholder="الاسم الكامل" required>
                            <label for="fullNameInput">الاسم الكامل</label>
                            @error('full_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" wire:model="employee_code" class="form-control" id="employeeCodeInput" placeholder="كود الموظف">
                            <label for="employeeCodeInput">كود الموظف</label>
                            @error('employee_code') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" wire:model="email" class="form-control" id="emailInput" placeholder="البريد الإلكتروني">
                            <label for="emailInput">البريد الإلكتروني</label>
                            @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="tel" wire:model="phone" class="form-control" id="phoneInput" placeholder="رقم الهاتف">
                            <label for="phoneInput">رقم الهاتف</label>
                            @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" wire:model="job_title" class="form-control" id="jobTitleInput" placeholder="المسمى الوظيفي">
                            <label for="jobTitleInput">المسمى الوظيفي</label>
                            @error('job_title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" wire:model="department" class="form-control" id="departmentInput" placeholder="القسم">
                            <label for="departmentInput">القسم</label>
                            @error('department') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select wire:model="employment_status" class="form-select" id="employmentStatusSelect">
                                <option value="">اختر</option>
                                <option value="دائم">دائم</option>
                                <option value="متعاقد">متعاقد</option>
                                <option value="تحت التدريب">تحت التدريب</option>
                            </select>
                            <label for="employmentStatusSelect">حالة التوظيف</label>
                            @error('employment_status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <select wire:model="employment_type" class="form-select" id="employmentTypeSelect">
                                <option value="">اختر</option>
                                <option value="دوام كامل">دوام كامل</option>
                                <option value="دوام جزئي">دوام جزئي</option>
                            </select>
                            <label for="employmentTypeSelect">نوع التوظيف</label>
                            @error('employment_type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" wire:model="salary" class="form-control" id="salaryInput" placeholder="الراتب">
                            <label for="salaryInput">الراتب</label>
                            @error('salary') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" wire:model="hiring_date" class="form-control" id="hiringDateInput">
                            <label for="hiringDateInput">تاريخ التعيين</label>
                            @error('hiring_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" wire:model="birth_date" class="form-control" id="birthDateInput">
                            <label for="birthDateInput">تاريخ الميلاد</label>
                            @error('birth_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <select wire:model="gender" class="form-select" id="genderSelect">
                                <option value="">اختر</option>
                                <option value="ذكر">ذكر</option>
                                <option value="أنثى">أنثى</option>
                            </select>
                            <label for="genderSelect">النوع</label>
                            @error('gender') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-floating">
                        <input type="text" wire:model="address" class="form-control" id="addressInput" placeholder="العنوان">
                        <label for="addressInput">العنوان</label>
                        @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-floating">
                        <textarea wire:model="notes" class="form-control" id="notesTextarea" placeholder="ملاحظات" style="height: 100px"></textarea>
                        <label for="notesTextarea">ملاحظات</label>
                        @error('notes') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-floating">
                        <select wire:model="status" class="form-select" id="statusSelect">
                            <option value="مفعل">مفعل</option>
                            <option value="غير مفعل">غير مفعل</option>
                        </select>
                        <label for="statusSelect">الحالة</label>
                        @error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3">
                 
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> حفظ الموظف
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>