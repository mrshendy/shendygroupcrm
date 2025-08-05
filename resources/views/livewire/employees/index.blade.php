<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h3 class="mb-1">إدارة الموظفين</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">الموظفين</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('employees.create') }}" class="btn btn-primary btn-lg rounded-pill shadow-sm px-4">
                <i class="mdi mdi-account-multiple-plus me-2"></i>إضافة موظف جديد
            </a>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="mdi mdi-search-web"></i></span>
                        <input type="text" wire:model.debounce.500ms="search" class="form-control border-start-0"
                            placeholder="ابحث عن موظف بالاسم، الكود أو البريد الإلكتروني...">
                    </div>
                </div>
               
            </div>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-header bg-light py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="mdi mdi-file-account me-2 text-primary"></i>سجل الموظفين
                </h5>

            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="50" class="fw-bold text-center">#</th>
                            <th class="fw-bold">الموظف</th>
                            <th class="fw-bold">المعلومات</th>
                            <th class="fw-bold">الوظيفة</th>
                            <th class="fw-bold text-center">الحالة</th>
                            <th width="150" class="fw-bold text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 position-relative">
                                            @if ($employee->avatar)
                                                <img src="{{ asset('storage/' . $employee->avatar) }}"
                                                    class="rounded-circle" width="40" height="40"
                                                    alt="صورة الموظف">
                                            @else
                                                <div
                                                    class="avatar bg-{{ $employee->status == 'مفعل' ? 'primary' : 'secondary' }} text-white rounded-circle">
                                                    {{ substr($employee->full_name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $employee->full_name }}</h6>
                                            <small class="text-muted">كود: {{ $employee->employee_code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <small class="text-muted d-block"><i class="mdi mdi-envelope me-1"></i>
                                            {{ $employee->email ?? '--' }}</small>
                                        <small class="text-muted"><i class="mdi mdi-phone me-1"></i>
                                            {{ $employee->phone ?? '--' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-semibold">{{ $employee->job_title }}</div>
                                        <small class="text-muted">{{ $employee->department }}</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill bg-{{ $employee->status == 'مفعل' ? 'success' : 'danger' }}">
                                        {{ $employee->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('employees.show', $employee->id) }}"
                                            class="btn btn-sm btn-outline-primary rounded-circle"
                                            data-bs-toggle="tooltip" title="عرض التفاصيل">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        <a href="{{ route('employees.edit', $employee->id) }}"
                                            class="btn btn-sm btn-outline-warning rounded-circle"
                                            data-bs-toggle="tooltip" title="تعديل البيانات">
                                            <i class="mdi mdi-pen"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="mdi mdi-user-slash fs-1 text-muted mb-3"></i>
                                        <h5 class="text-muted mb-2">لا يوجد موظفين</h5>
                                        <p class="text-muted">قم بإضافة موظفين جديدين للبدء</p>
                                        <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary mt-2">
                                            <i class="mdi mdi-plus me-1"></i> إضافة موظف
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
           </div> <!-- نهاية card-body -->

        <!-- ✅ Pagination -->
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-center">
                {{ $employees->links() }}
            </div>
        </div>

    </div> <!-- نهاية card -->
</div> <!-- نهاية container -->



        <style>
            .avatar {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                font-weight: 600;
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
            }

            .table th {
                font-size: 0.85rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                border-top: none;
                border-bottom: 1px solid #dee2e6;
            }

            .table td {
                vertical-align: middle;
            }

            .rounded-circle {
                width: 32px;
                height: 32px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .rounded-3 {
                border-radius: 0.75rem !important;
            }

            .badge {
                padding: 0.35em 0.65em;
                font-size: 0.75em;
                font-weight: 500;
            }
        </style>

        <script>
            // Initialize tooltips
            document.addEventListener('DOMContentLoaded', function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            })
        </script>
