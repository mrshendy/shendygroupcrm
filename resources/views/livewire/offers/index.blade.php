<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">العروض</li>
                </ol>
            </nav>
        </div>
        @can('offer-create')
        <a href="{{ route('offers.create') }}" class="btn btn-primary rounded-pill shadow-sm">
            <i class="mdi mdi-plus-circle-outline me-1"></i>عرض جديد
        </a>
        @endcan
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-primary border-4 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted fw-semibold">إجمالي العروض</h6>
                            <h3 class="mb-0">{{ $totalOffers }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded ms-3">
                            <i class="mdi mdi-file-document text-primary fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-info border-4 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted fw-semibold">عروض جديدة</h6>
                            <h3 class="mb-0">{{ $newOffers }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded ms-3">
                            <i class="mdi mdi-file-plus text-info fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-danger border-4 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted fw-semibold">عروض مرفوضة</h6>
                            <h3 class="mb-0">{{ $rejectedOffers }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded ms-3">
                            <i class="mdi mdi-file-remove text-danger fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-warning border-4 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted fw-semibold">عروض مغلقة</h6>
                            <h3 class="mb-0">{{ $closedOffers }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded ms-3">
                            <i class="mdi mdi-file-lock text-warning fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Offers Table -->
    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-table me-2"></i>قائمة العروض
                </h5>
               
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="fw-semibold">#</th>
                            <th class="fw-semibold">العميل</th>
                            <th class="fw-semibold">المشروع</th>
                            <th class="fw-semibold">تاريخ العرض</th>
                            <th class="fw-semibold">تاريخ الانتهاء</th>
                            <th class="fw-semibold">الحالة</th>
                            <th class="fw-semibold">آخر متابع</th>
                            <th class="fw-semibold">المنشئ</th>
                            <th class="fw-semibold text-end">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($offers as $offer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="avatar bg-primary text-white rounded-circle">
                                            {{ substr($offer->client->name ?? '?', 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $offer->client->name ?? '-' }}</h6>
                                        <small class="text-muted">{{ $offer->client->company ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $offer->project->name ?? '-' }}</td>
                            <td>{{ $offer->start_date }}</td>
                            <td>{{ $offer->end_date }}</td>
                            <td>
                                @php
                                    $statusText = match ($offer->status) {
                                        'new' => 'جديد',
                                        'under_review' => 'تحت المتابعة',
                                        'approved' => 'تمت الموافقة',
                                        'contracting' => 'جارٍ التعاقد',
                                        'rejected' => 'مرفوض',
                                        'pending' => 'قيد الانتظار',
                                        'signed' => 'متعاقد',
                                        'closed' => 'مغلق',
                                        default => 'غير معروف',
                                    };

                                    $statusClass = match ($offer->status) {
                                        'new' => 'info',
                                        'under_review' => 'primary',
                                        'approved' => 'success',
                                        'contracting' => 'success',
                                        'rejected' => 'danger',
                                        'pending' => 'secondary',
                                        'signed' => 'success',
                                        'closed' => 'warning',
                                        default => 'dark',
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }}">
                                    <i class="mdi mdi-{{ $statusClass == 'success' ? 'check' : ($statusClass == 'warning' ? 'clock' : 'close') }}-circle-outline me-1"></i>
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td>-</td>
                            <td>{{ Auth::user()->name }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                              @can('offer-show')      <a href="{{ route('offers.show', $offer->id) }}" class="btn btn-sm btn-light rounded-circle" data-bs-toggle="tooltip" title="عرض">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </a>
                                    @endcan
                                    @can('offer-edit')
                                    <a href="{{ route('offers.edit', $offer->id) }}" class="btn btn-sm btn-light rounded-circle" data-bs-toggle="tooltip" title="تعديل">
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </a>
                                    @endcan
                                    @can('offer-delete')
                                    <button class="btn btn-sm btn-light rounded-circle" 
                                        onclick="if(!confirm('هل أنت متأكد من حذف هذا العرض؟')) { event.stopImmediatePropagation(); }"
                                        wire:click="delete({{ $offer->id }})" data-bs-toggle="tooltip" title="حذف">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </button>
                                    @endcan
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                            @can('offer-followup') <a class="dropdown-item" href="{{ route('offers.followup', $offer->id) }}">
                                                    <i class="mdi mdi-chat-processing-outline me-2"></i>متابعة
                                                </a>
                                            @endcan
                                            </li>
                                            <li>
                                              @can('offer-status')  <a class="dropdown-item" href="{{ route('offers.status', $offer) }}">
                                                    <i class="mdi mdi-swap-horizontal me-2"></i>تغيير الحالة
                                                </a>
                                            @endcan
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
      
    </div>
</div>

<style>
    .avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
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
    
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.05);
    }
    
    .table th {
        border-top: none;
        border-bottom: 2px solid #dee2e6;
    }
    
    .btn-light {
        background-color: #f8f9fa;
        border-color: #f8f9fa;
    }
    
    .btn-light:hover {
        background-color: #e9ecef;
        border-color: #e9ecef;
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
    
    .bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    .bg-info-subtle {
        background-color: rgba(13, 202, 240, 0.1);
    }
    
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1);
    }
    
    .bg-warning-subtle {
        background-color: rgba(255, 193, 7, 0.1);
    }
    
    .bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.1);
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
    
    window.addEventListener('offerDeleted', () => {
        const toast = new bootstrap.Toast(document.getElementById('deleteToast'));
        toast.show();
    });
</script>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="deleteToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="mdi mdi-check-circle-outline me-2"></i> تم حذف العرض بنجاح
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>