<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
         
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href=""><i class="mdi mdi-home"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">العملاء</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('clients.create') }}" class="btn btn-primary rounded-pill shadow-sm">
            <i class="mdi mdi-plus-circle-outline me-1"></i>عميل جديد
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="mdi mdi-magnify"></i>
                        </span>
                        <input wire:model="search" type="text" class="form-control" placeholder="ابحث باسم العميل أو البريد...">
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
                        <th class="fw-semibold">العميل</th>
                        <th class="fw-semibold">معلومات التواصل</th>
                        <th class="fw-semibold">الحالة</th>
                        <th class="fw-semibold text-end">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="avatar bg-primary text-white rounded-circle">
                                        {{ substr($client->name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $client->name }}</h6>
                                    <small class="text-muted">ID: {{ $client->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <div class="mb-1">
                                    <i class="mdi mdi-email-outline me-2 text-muted"></i>
                                    {{ $client->email }}
                                </div>
                                <div>
                                    <i class="mdi mdi-phone-outline me-2 text-muted"></i>
                                    {{ $client->phone }}
                                </div>
                            </div>
                        </td>
                        <td>
                            @if ($client->status == 'new')
                            <span class="badge bg-primary-subtle text-secondary">
                                <i class="mdi mdi-account-plus-outline me-1"></i>جديد
                            </span>
                            @elseif($client->status == 'in_progress')
                            <span class="badge bg-warning-subtle text-warning">
                                <i class="mdi mdi-account-clock-outline me-1"></i>جاري العمل
                            </span>
                            @elseif($client->status == 'closed')
                            <span class="badge bg-danger-subtle text-danger">
                                <i class="mdi mdi-account-cancel-outline me-1"></i>موقوف
                            </span>
                            @else
                            <span class="badge bg-secondary-subtle text-secondary">
                                <i class="mdi mdi-account-question-outline me-1"></i>غير معروف
                            </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-light rounded-circle" data-bs-toggle="tooltip" title="تعديل">
                                    <i class="mdi mdi-pencil-outline"></i>
                                </a>
                                <button wire:click="confirmDelete({{ $client->id }})" class="btn btn-sm btn-light rounded-circle" data-bs-toggle="tooltip" title="حذف">
                                    <i class="mdi mdi-delete-outline"></i>
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="mdi mdi-eye-outline me-2"></i>عرض التفاصيل
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="mdi mdi-note-plus-outline me-2"></i>إضافة ملاحظة
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="mdi mdi-account-off-outline fs-1 text-muted mb-2"></i>
                                <h5 class="text-muted">لا يوجد عملاء</h5>
                                <p class="text-muted small">قم بإضافة عميل جديد لبدء العمل</p>
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
                    عرض <span class="fw-semibold">{{ $clients->firstItem() }}</span> إلى <span class="fw-semibold">{{ $clients->lastItem() }}</span> من <span class="fw-semibold">{{ $clients->total() }}</span> عميل
                </div>
                <div>
                    {{ $clients->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

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
    }
    
    .breadcrumb-item a {
        color: #6c757d;
        text-decoration: none;
    }
    
    .breadcrumb-item.active {
        color: #4361ee;
    }
    
    .table th {
        border-top: none;
        border-bottom: 2px solid #dee2e6;
    }
    
    .rounded-circle {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .badge {
        padding: 0.35rem 0.65rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }
</style>

<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    })
</script>