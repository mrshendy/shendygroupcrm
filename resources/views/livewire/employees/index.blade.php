<div class="container-fluid px-4 py-4">
    <!-- Header Section - Enhanced Design -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h3 class="mb-1 text-dark fw-bold"><i class="mdi mdi-account-group-outline me-2 text-primary"></i>إدارة
                الموظفين</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none"><i
                                class="mdi mdi-home-outline"></i></a></li>
                    <li class="breadcrumb-item active " aria-current="page"><i
                            class="mdi mdi-account-multiple me-1"></i>الموظفين</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('employee-create')
                <a href="{{ route('employees.create') }}"
                    class="btn btn-primary btn-lg rounded-pill shadow-sm px-4 d-flex align-items-center">
                    <i class="mdi mdi-account-plus-outline me-2"></i>إضافة موظف جديد
                </a>
            @endcan
            <div class="dropdown">
                @can('employee-procedures')
                    <button
                        class="btn btn-outline-secondary btn-lg rounded-pill shadow-sm px-4 dropdown-toggle d-flex align-items-center"
                        type="button" id="actionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-cog-outline me-2"></i>الإجراءات
                    </button>
                @endcan
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="actionsDropdown">
                    @can('employee-attendance')
                        <li><a class="dropdown-item d-flex align-items-center py-2"
                                href="{{ route('attendance.manage') }}"><i
                                    class="mdi mdi-clock-check-outline me-2 text-primary"></i>ادارة الحضور</a></li>
                    @endcan
                    @can('employee-salary')
                        <li><a class="dropdown-item d-flex align-items-center py-2"
                                href="{{ route('employees.salaries') }}"><i
                                    class="mdi mdi-cash-multiple me-2 text-success"></i>المرتبات</a></li>
                    @endcan
                    @can('employee-leave')
                        <li><a class="dropdown-item d-flex align-items-center py-2"
                                href="{{ route('employees.leaves') }}"><i
                                    class="mdi mdi-calendar me-2 text-warning"></i>الإجازات</a></li>
                    @endcan
                    @can('employee-shift')
                        <li><a class="dropdown-item d-flex align-items-center py-2" href="{{ route('shifts.manage') }}"><i
                                    class="mdi mdi-calendar-clock me-2 text-info"></i>الشيفتات</a></li>
                    @endcan
                </ul>
            </div>
        </div>
    </div>

    <!-- Search and Filters - Enhanced Design -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-8">
                    <div class="input-group border rounded-pill bg-light">
                        <span class="input-group-text bg-transparent border-0"><i
                                class="mdi mdi-magnify text-muted"></i></span>
                        <input type="text" wire:model.debounce.500ms="search"
                            class="form-control border-0 shadow-none bg-transparent"
                            placeholder="ابحث عن موظف بالاسم، الكود أو البريد الإلكتروني...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Employees Table - Enhanced Design -->
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-header bg-light py-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="mdi mdi-table-account me-2 text-primary"></i>سجل الموظفين
                </h5>
                <div class="text-muted small d-flex align-items-center">
                    <i class="mdi mdi-information-outline me-1"></i>
                    إجمالي الموظفين: <span class="fw-bold mx-1">{{ $employees->total() }}</span>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="50" class="fw-bold text-center">#</th>
                            <th class="fw-bold"><i class="mdi mdi-account-outline me-1"></i>الموظف</th>
                            <th class="fw-bold"><i class="mdi mdi-information-outline me-1"></i>المعلومات</th>
                            <th class="fw-bold"><i class="mdi mdi-briefcase-outline me-1"></i>الوظيفة</th>
                            <th class="fw-bold text-center"><i class="mdi mdi-account-check-outline me-1"></i>الحالة
                            </th>
                            <th width="150" class="fw-bold text-center"><i
                                    class="mdi mdi-cog-outline me-1"></i>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr class="border-bottom">
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 position-relative">
                                            @if ($employee->avatar)
                                                <img src="{{ asset('storage/' . $employee->avatar) }}"
                                                    class="rounded-circle border shadow-sm" width="40"
                                                    height="40" alt="صورة الموظف">
                                                @if ($employee->status == 'مفعل')
                                                    <span
                                                        class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1 border-2 border-white"></span>
                                                @else
                                                    <span
                                                        class="position-absolute bottom-0 end-0 bg-danger rounded-circle p-1 border-2 border-white"></span>
                                                @endif
                                            @else
                                                <div
                                                    class="avatar bg-{{ $employee->status == 'مفعل' ? 'primary' : 'secondary' }} text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm">
                                                    {{ substr($employee->full_name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $employee->full_name }}</h6>
                                            <small class="text-muted"><i class="mdi mdi-identifier me-1"></i>كود:
                                                {{ $employee->employee_code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <small class="text-muted d-block"><i
                                                class="mdi mdi-email-outline me-1"></i>{{ $employee->email ?? '--' }}</small>
                                        <small class="text-muted"><i
                                                class="mdi mdi-phone-outline me-1"></i>{{ $employee->phone ?? '--' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-semibold"><i
                                                class="mdi mdi-briefcase-outline me-1 text-secondary"></i>{{ $employee->job_title }}
                                        </div>
                                        <small class="text-muted"><i
                                                class="mdi mdi-office-building-outline me-1 text-secondary"></i>{{ $employee->department }}</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill bg-{{ $employee->status == 'مفعل' ? 'success' : 'danger' }} d-inline-flex align-items-center shadow-sm">
                                        <i
                                            class="mdi mdi-{{ $employee->status == 'مفعل' ? 'check-circle-outline' : 'close-circle-outline' }} me-1"></i>
                                        {{ $employee->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('employee-view')
                                            <a href="{{ route('employees.show', $employee->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-circle p-2 shadow-sm"
                                                data-bs-toggle="tooltip" title="عرض التفاصيل">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </a>
                                        @endcan
                                        @can('employee-edit')
                                            <a href="{{ route('employees.edit', $employee->id) }}"
                                                class="btn btn-sm btn-outline-warning rounded-circle p-2 shadow-sm"
                                                data-bs-toggle="tooltip" title="تعديل البيانات">
                                                <i class="mdi mdi-pencil-outline"></i>
                                            </a>
                                        @endcan
                                        @can('employee-delete')
                                            <button class="btn btn-sm btn-outline-danger rounded-circle p-2 shadow-sm"
                                                data-bs-toggle="tooltip" title="حذف الموظف">
                                                <i class="mdi mdi-trash-can-outline"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="mdi mdi-account-off-outline fs-1 text-muted mb-3"></i>
                                        <h5 class="text-muted mb-2">لا يوجد موظفين</h5>
                                        <p class="text-muted">قم بإضافة موظفين جديدين للبدء</p>
                                        <a href="{{ route('employees.create') }}"
                                            class="btn btn-primary mt-2 rounded-pill shadow-sm">
                                            <i class="mdi mdi-plus me-1"></i> إضافة موظف جديد
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination - Enhanced Design -->
        <div class="card-footer bg-white py-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    عرض <span class="fw-bold">{{ $employees->firstItem() }}</span> إلى <span
                        class="fw-bold">{{ $employees->lastItem() }}</span> من <span
                        class="fw-bold">{{ $employees->total() }}</span> موظف
                </div>
                <div>
                    {{ $employees->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar {
        width: 40px;
        height: 40px;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .breadcrumb {
        background-color: transparent;
        padding: 0;
        font-size: 0.9rem;
    }

    .breadcrumb-item a {
        color: #6c757d;
        text-decoration: none;
        transition: all 0.2s;
    }

    .breadcrumb-item a:hover {
        color: #0d6efd;
    }

    .breadcrumb-item.active {
        color: #0d6efd;
        font-weight: 500;
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .table th {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-top: none;
        border-bottom: 1px solid #dee2e6;
        white-space: nowrap;
    }

    .table td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }

    .rounded-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .badge {
        padding: 0.45em 0.75em;
        font-size: 0.75em;
        font-weight: 500;
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        border-radius: 0.5rem;
    }

    .dropdown-item {
        transition: all 0.2s;
    }

    .dropdown-item:hover {
        background-color: rgba(13, 110, 253, 0.1);
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .pagination .page-link {
        color: #0d6efd;
        border-radius: 0.375rem;
    }

    .input-group-text {
        background-color: transparent;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }

    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
</style>

<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover'
            })
        })
    })
</script>
