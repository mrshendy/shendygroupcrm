<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <!-- Card Header -->
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0">
                    <i class="mdi mdi-notebook-edit-outline me-2 text-primary"></i>
                    متابعة العرض: {{ $offer->client->name ?? '-' }}
                </h3>
                <a href="{{ route('offers.show', $offer->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle">
                    <i class="mdi mdi-arrow-left"></i>
                </a>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <!-- Add New Followup Form -->
            <div class="card border-0 bg-light-subtle mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-4">
                        <i class="mdi mdi-plus-circle-outline me-2 text-primary"></i>
                        إضافة متابعة جديدة
                    </h5>
                    <form wire:submit.prevent="saveFollowup">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-calendar-clock-outline me-1 text-muted"></i>
                                    تاريخ المتابعة
                                </label>
                                <input type="date" class="form-control" wire:model.defer="follow_up_date">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-form-select me-1 text-muted"></i>
                                    نوع المتابعة
                                </label>
                                <select class="form-select" wire:model.defer="type">
                                    <option value="">اختر النوع</option>
                                    <option value="call">
                                        <i class="mdi mdi-phone-outline"></i> اتصال
                                    </option>
                                    <option value="email">
                                        <i class="mdi mdi-email-outline"></i> بريد إلكتروني
                                    </option>
                                    <option value="meeting">
                                        <i class="mdi mdi-account-group-outline"></i> اجتماع
                                    </option>
                                    <option value="visit">
                                        <i class="mdi mdi-map-marker-outline"></i> زيارة
                                    </option>
                                </select>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-text-box-outline me-1 text-muted"></i>
                                    الوصف
                                </label>
                                <textarea class="form-control" rows="3" wire:model.defer="description" placeholder="أدخل تفاصيل المتابعة..."></textarea>
                            </div>
                            
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                    <i class="mdi mdi-content-save-outline me-1"></i> حفظ المتابعة
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Followups History -->
            <div class="card border-0 bg-light-subtle">
                <div class="card-body">
                    <h5 class="fw-semibold mb-4">
                        <i class="mdi mdi-history me-2 text-primary"></i>
                        سجل المتابعات
                    </h5>
                    
                    @forelse($followups as $f)
                    <div class="timeline-item border-bottom pb-3 mb-3">
                        <div class="d-flex">
                            <div class="me-3">
                                @if($f->type == 'call')
                                <div class="avatar bg-primary text-white rounded-circle">
                                    <i class="mdi mdi-phone"></i>
                                </div>
                                @elseif($f->type == 'email')
                                <div class="avatar bg-success text-white rounded-circle">
                                    <i class="mdi mdi-email"></i>
                                </div>
                                @elseif($f->type == 'meeting')
                                <div class="avatar bg-warning text-white rounded-circle">
                                    <i class="mdi mdi-account-group"></i>
                                </div>
                                @else
                                <div class="avatar bg-info text-white rounded-circle">
                                    <i class="mdi mdi-map-marker"></i>
                                </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1 fw-semibold">
                                        {{ ucfirst($f->type) }} - {{ $f->follow_up_date }}
                                    </h6>
                                    <small class="text-muted">{{ $f->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">{{ $f->description }}</p>
                                <small class="text-muted">
                                    <i class="mdi mdi-account-outline"></i> بواسطة {{ $f->user->name ?? 'نظام' }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="mdi mdi-notebook-remove-outline fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد متابعات مسجلة</h5>
                        <p class="text-muted small">قم بإضافة متابعة جديدة لبدء التسجيل</p>
                    </div>
                    @endforelse
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
    }
    
    .bg-light-subtle {
        background-color: #f8f9fa;
    }
    
    .rounded-4 {
        border-radius: 0.75rem;
    }
    
    .timeline-item {
        position: relative;
    }
    
    .timeline-item:last-child {
        border-bottom: none !important;
    }
    
    .form-select option {
        padding: 0.5rem;
    }
</style>