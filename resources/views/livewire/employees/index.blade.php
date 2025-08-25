<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h3 class="mb-1 text-dark fw-bold">
                <i class="mdi mdi-account-group-outline me-2 text-primary"></i>إدارة الموظفين
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#" class="text-decoration-none"><i class="mdi mdi-home-outline"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="mdi mdi-account-multiple me-1"></i>الموظفين
                    </li>
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
                    <button class="btn btn-outline-secondary btn-lg rounded-pill shadow-sm px-4 dropdown-toggle d-flex align-items-center"
                            type="button" id="actionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-cog-outline me-2"></i>الإجرءات الأساسية للموظفين
                    </button>
                @endcan
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="actionsDropdown">
                    @can('employee-attendance')
                        <li><a class="dropdown-item d-flex align-items-center py-2"
                               href="{{ route('attendance.manage') }}"><i
                                    class="mdi mdi-clock-check-outline me-2 text-warning"></i>ادارة الحضور</a></li>
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
                        <li><a class="dropdown-item d-flex align-items-center py-2"
                               href="{{ route('shifts.manage') }}"><i
                                    class="mdi mdi-calendar-clock me-2 text-info"></i>الشيفتات</a></li>
                    @endcan
                </ul>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-8">
                    <div class="input-group border rounded-pill bg-light">
                        <span class="input-group-text bg-transparent border-0">
                            <i class="mdi mdi-magnify text-muted"></i>
                        </span>
                        <input type="text" wire:model.debounce.500ms="search"
                               class="form-control border-0 shadow-none bg-transparent"
                               placeholder="ابحث عن موظف بالاسم، الكود أو البريد الإلكتروني...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Employees Table -->
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
                        <th class="text-center">#</th>
                        <th>الموظف</th>
                        <th>المعلومات</th>
                        <th>الوظيفة</th>
                        <th class="text-center">الحالة</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td class="text-center text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if ($employee->avatar)
                                            <img src="{{ asset('storage/' . $employee->avatar) }}"
                                                 class="rounded-circle border shadow-sm" width="40" height="40">
                                        @else
                                            <div class="avatar bg-primary text-white rounded-circle shadow-sm"
                                                 style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
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
                                <small class="d-block text-muted">{{ $employee->email ?? '--' }}</small>
                                <small class="text-muted">{{ $employee->phone ?? '--' }}</small>
                            </td>
                            <td>{{ $employee->job_title }} <br><small class="text-muted">{{ $employee->department }}</small></td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-{{ $employee->status == 'مفعل' ? 'success' : 'danger' }}">
                                    {{ $employee->status }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    @can('employee-show')
                                        <a href="{{ route('employees.show', $employee->id) }}"
                                           class="btn btn-sm btn-outline-primary rounded-circle p-2 shadow-sm"
                                           title="عرض">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </a>
                                    @endcan
                                    @can('employee-edit')
                                        <a href="{{ route('employees.edit', $employee->id) }}"
                                           class="btn btn-sm btn-outline-warning rounded-circle p-2 shadow-sm"
                                           title="تعديل">
                                            <i class="mdi mdi-pencil-outline"></i>
                                        </a>
                                    @endcan
                                    @can('employee-delete')
                                        <button wire:click="confirmDelete({{ $employee->id }})"
                                                class="btn btn-sm btn-outline-danger rounded-circle p-2 shadow-sm"
                                                title="حذف">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">لا يوجد موظفين</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if ($employees->hasPages())
            <div class="card-footer bg-white py-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        عرض <strong>{{ $employees->firstItem() }}</strong> إلى
                        <strong>{{ $employees->lastItem() }}</strong> من
                        <strong>{{ $employees->total() }}</strong> موظف
                    </div>
                    {{ $employees->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title"><i class="mdi mdi-alert-circle-outline me-2"></i> تأكيد الحذف</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">هل أنت متأكد أنك تريد حذف هذا الموظف؟ لا يمكن التراجع عن العملية.</div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" wire:click="delete">نعم، احذف</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('showDeleteModal', () => {
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    });

    window.addEventListener('hideDeleteModal', () => {
        let modalEl = document.getElementById('deleteModal');
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    });
</script>
