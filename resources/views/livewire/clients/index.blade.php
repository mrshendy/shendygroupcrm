<div class="container-fluid px-4" dir="rtl">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h4 class="mb-0 fw-bold text-primary">
                <i class="mdi mdi-account-group-outline me-2"></i>
                إدارة العملاء
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home"></i> الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">العملاء</li>
                </ol>
            </nav>
        </div>
      @can('client-create')
          
       <a href="{{ route('clients.create') }}" class="btn btn-primary rounded-pill shadow-sm px-4">
            <i class="mdi mdi-plus-circle-outline me-1"></i> عميل جديد
        </a>
         @endcan
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-light bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-muted">إجمالي العملاء</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($totalClients) }}</h3>
                        </div>
                        <span class="avatar bg-primary text-white rounded-circle">
                            <i class="mdi mdi-account-group"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-muted">العملاء الجدد</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($newClients) }}</h3>
                        </div>
                        <span class="avatar bg-info text-white rounded-circle">
                            <i class="mdi mdi-account-plus"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-muted">العملاء النشطون</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($activeClients) }}</h3>
                        </div>
                        <span class="avatar bg-warning text-white rounded-circle">
                            <i class="mdi mdi-account-clock"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-muted">العملاء الموقوفون</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($closedClients) }}</h3>
                        </div>
                        <span class="avatar bg-success text-white rounded-circle">
                            <i class="mdi mdi-account-check"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <input wire:model.debounce.400ms="search" type="text" class="form-control"
                            placeholder="ابحث باسم العميل أو البريد...">
                        <span class="input-group-text bg-light">
                            <i class="mdi mdi-magnify"></i>
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Clients Table -->
    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="fw-semibold" width="25%">العميل</th>
                        <th class="fw-semibold" width="25%">معلومات التواصل</th>
                        <th class="fw-semibold" width="15%">الحالة</th>
                        <th class="fw-semibold" width="15%">تاريخ الإضافة</th>
                        <th class="fw-semibold text-end" width="20%">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        @php $status = strtolower($client->status ?? ''); @endphp
                        <tr class="align-middle">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="avatar bg-primary text-white rounded-circle">
                                            {{ mb_substr($client->name ?? '؟', 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $client->name }}</h6>
                                        <small class="text-muted">ID: {{ $client->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <div class="mb-1">
                                        <i class="mdi mdi-email-outline me-2 text-muted"></i>
                                        {{ $client->email ?: '—' }}
                                    </div>
                                    <div>
                                        <i class="mdi mdi-phone-outline me-2 text-muted"></i>
                                        {{ $client->phone ?: '—' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                @switch($status)
                                    @case('new')
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            <i class="mdi mdi-account-plus-outline me-1"></i> جديد
                                        </span>
                                    @break

                                    @case('under_implementation')
                                    @case('under implementation')
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                            <i class="mdi mdi-account-clock-outline me-1"></i> قيد التنفيذ
                                        </span>
                                    @break

                                    @case('active')
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <i class="mdi mdi-account-check-outline me-1"></i> نشط
                                        </span>
                                    @break

                                    @case('closed')
                                    @case('blocked')
                                        <span class="badge bg-danger bg-opacity-10 text-danger">
                                            <i class="mdi mdi-account-cancel-outline me-1"></i> موقوف
                                        </span>
                                    @break

                                    @default
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            <i class="mdi mdi-account-question-outline me-1"></i> غير معروف
                                        </span>
                                @endswitch
                            </td>
                            <td>{{ optional($client->created_at)->format('Y-m-d') }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    @can('client-show')
                                        <a href="{{ route('clients.show', $client->id) }}"
                                            class="btn btn-sm btn-light rounded-circle" data-bs-toggle="tooltip"
                                            title="عرض الملف">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </a>
                                    @endcan

                                    @can('client-edit')
                                        <a href="{{ route('clients.edit', $client->id) }}"
                                            class="btn btn-sm btn-light rounded-circle" data-bs-toggle="tooltip"
                                            title="تعديل">
                                            <i class="mdi mdi-pencil-outline"></i>
                                        </a>
                                    @endcan

                                    @can('client-delete')
                                        <button type="button" wire:click="confirmDelete('{{ $client->id }}')"
                                            class="btn btn-sm btn-light rounded-circle" data-bs-toggle="tooltip"
                                            title="حذف">
                                            <i class="mdi mdi-delete-outline"></i>
                                        </button>
                                    @endcan
                                </div>

                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="mdi mdi-account-off-outline fs-1 text-muted mb-3"></i>
                                        <h5 class="text-muted mb-2">لا يوجد عملاء</h5>
                                        <p class="text-muted small mb-0">قم بإضافة عميل جديد لبدء العمل</p>
                                        <a href="{{ route('clients.create') }}" class="btn btn-sm btn-primary mt-3">
                                            <i class="mdi mdi-plus-circle-outline me-1"></i> إضافة عميل جديد
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        عرض <span class="fw-semibold">{{ $clients->firstItem() }}</span> إلى
                        <span class="fw-semibold">{{ $clients->lastItem() }}</span> من
                        <span class="fw-semibold">{{ $clients->total() }}</span> عميل
                    </div>
                    <div>
                        {{ $clients->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger">
                        <i class="mdi mdi-alert-circle-outline me-2"></i> تأكيد الحذف
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>هل أنت متأكد من رغبتك في حذف هذا العميل؟ سيتم حذف جميع البيانات المرتبطة به ولا يمكن استرجاعها.</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteClient" data-bs-dismiss="modal">
                        <i class="mdi mdi-delete-outline me-1"></i> نعم، احذف
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });

                // Delete confirmation modal
                window.addEventListener('showDeleteModal', event => {
                    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    deleteModal.show();
                });
            });
        </script>
    @endpush

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
            font-size: 0.875rem;
        }

        .breadcrumb-item a {
            color: #6c757d;
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb-item a:hover {
            color: #4361ee;
        }

        .breadcrumb-item.active {
            color: #4361ee;
            font-weight: 500;
        }

        .table th {
            border-top: none;
            border-bottom: 2px solid #dee2e6;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            transition: all 0.2s;
        }

        .rounded-circle:hover {
            transform: scale(1.1);
        }

        .badge {
            padding: 0.35rem 0.65rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            font-size: 0.75rem;
            border-radius: 50px;
        }
    </style>
