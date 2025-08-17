<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">

        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-edit me-1"></i> تعديل البيانات
        </a>
    </div>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-body p-0">
            <div class="row g-0">
                <!-- قسم الصورة -->
                <div class="col-md-3 bg-light p-4 text-center">
                    <div class="position-relative">
                        @if ($employee->avatar)
                            <img src="{{ asset('storage/' . $employee->avatar) }}"
                                class="img-thumbnail rounded-circle border-primary" width="180" height="180"
                                alt="صورة الموظف">
                        @else
                            <img src="{{ asset('images/default-avatar.png') }}"
                                class="img-thumbnail rounded-circle border-primary" width="180" height="180"
                                alt="صورة افتراضية">
                        @endif
                        <span
                            class="position-absolute bottom-0 start-50 translate-x--50 bg-{{ $employee->status == 'مفعل' ? 'success' : 'danger' }} text-white px-2 py-1 rounded-pill small">
                            {{ $employee->status }}
                        </span>
                    </div>

                    <h5 class="mt-3 mb-1">{{ $employee->full_name }}</h5>
                    <p class="text-muted small mb-2">{{ $employee->job_title }}</p>


                </div>

                <!-- قسم البيانات -->
                <div class="col-md-9 p-4">
                    <div class="row">
                        <!-- العمود الأول -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted small mb-1">المعلومات الأساسية</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <span class="d-block text-muted small">كود الموظف</span>
                                        <strong>{{ $employee->employee_code ?? '--' }}</strong>
                                    </li>
                                    <li class="mb-2">
                                        <span class="d-block text-muted small">القسم</span>
                                        <strong>{{ $employee->department ?? '--' }}</strong>
                                    </li>
                                    <li class="mb-2">
                                        <span class="d-block text-muted small">تاريخ الميلاد</span>
                                        <strong>{{ $employee->birth_date ? date('Y/m/d', strtotime($employee->birth_date)) : '--' }}</strong>
                                    </li>
                                    <li class="mb-2">
                                        <span class="d-block text-muted small">النوع</span>
                                        <strong>{{ $employee->gender ?? '--' }}</strong>
                                    </li>
                                </ul>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-muted small mb-1">معلومات التوظيف</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <span class="d-block text-muted small">تاريخ التعيين</span>
                                        <strong>{{ $employee->hiring_date ? date('Y/m/d', strtotime($employee->hiring_date)) : '--' }}</strong>
                                    </li>
                                    <li class="mb-2">
                                        <span class="d-block text-muted small">حالة التوظيف</span>
                                        <strong>{{ $employee->employment_status ?? '--' }}</strong>
                                    </li>
                                    <li class="mb-2">
                                        <span class="d-block text-muted small">نوع التوظيف</span>
                                        <strong>{{ $employee->employment_type ?? '--' }}</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- العمود الثاني -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted small mb-1">المعلومات المالية</h6>
                                <div class="bg-light p-3 rounded">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted small">الراتب الأساسي</span>
                                        <strong class="text-success">{{ number_format($employee->salary, 2) }}
                                            جنيه</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-muted small mb-1">معلومات الاتصال</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <span class="d-block text-muted small">البريد الإلكتروني</span>
                                        <strong>{{ $employee->email ?? '--' }}</strong>
                                    </li>
                                    <li class="mb-2">
                                        <span class="d-block text-muted small">رقم الهاتف</span>
                                        <strong>{{ $employee->phone ?? '--' }}</strong>
                                    </li>
                                    <li class="mb-2">
                                        <span class="d-block text-muted small">العنوان</span>
                                        <strong>{{ $employee->address ?? '--' }}</strong>
                                    </li>
                                </ul>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-muted small mb-1">ملاحظات</h6>
                                <div class="bg-light p-3 rounded">
                                    @if ($employee->notes)
                                        {{ $employee->notes }}
                                    @else
                                        <span class="text-muted">لا توجد ملاحظات</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- تذييل البطاقة -->
        <div class="card-footer bg-light d-flex justify-content-between">
            <small class="text-muted">
                آخر تحديث: {{ optional($employee->updated_at)->diffForHumans() ?? 'لم يتم التحديث بعد' }}
            </small>

        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
    }

    .img-thumbnail {
        border-width: 2px;
        object-fit: cover;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
