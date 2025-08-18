<div class="container-fluid p-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center" style="background-color: #f8f9fa!important;">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-beach me-2 fs-4" style="color: #5d87ff;"></i>
                <h5 class="mb-0 fw-bold" style="color: #5d87ff;">إدارة الإجازات</h5>
            </div>
            <button class="btn px-4 py-2 rounded-pill shadow-sm" style="background-color: #5d87ff; border-color: #5d87ff;">
                <i class="mdi mdi-account-clock-outline me-1"></i>
                <a href="{{ route('leave-balances.manage') }}" class="text-white text-decoration-none">رصيد الإجازات</a>
            </button>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light" style="background-color: #f5f8fb!important;">
                        <tr>
                            <th class="fw-bold text-start ps-4"><i class="mdi mdi-account-outline me-1" style="color: #6c757d;"></i> الموظف</th>
                            <th class="fw-bold text-center"><i class="mdi mdi-format-list-bulleted-type me-1" style="color: #6c757d;"></i> النوع</th>
                            <th class="fw-bold text-center"><i class="mdi mdi-calendar-start me-1" style="color: #6c757d;"></i> من</th>
                            <th class="fw-bold text-center"><i class="mdi mdi-calendar-end me-1" style="color: #6c757d;"></i> إلى</th>
                            <th class="fw-bold text-center"><i class="mdi mdi-comment-text-outline me-1" style="color: #6c757d;"></i> السبب</th>
                            <th class="fw-bold text-center"><i class="mdi mdi-clipboard-check-outline me-1" style="color: #6c757d;"></i> الحالة</th>
                            <th class="fw-bold text-center"><i class="mdi mdi-check-circle-outline me-1" style="color: #39cb7f;"></i> المسموح</th>
                            <th class="fw-bold text-center"><i class="mdi mdi-alert-circle-outline me-1" style="color: #fc4b6c;"></i> المستهلك</th>
                            <th class="fw-bold text-center pe-4"><i class="mdi mdi-timer-sand-empty me-1" style="color: #5d87ff;"></i> المتبقي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaves as $leave)
                            @php
                                $balance = \App\Models\LeaveBalance::where('employee_id', $leave->employee_id)
                                    ->where('year', now()->year)
                                    ->first();

                                $allowed   = $balance?->annual_days + $balance?->casual_days ?? 0;
                                $used      = $balance?->used_days ?? 0;
                                $remaining = $balance?->remaining_days ?? 0;
                            @endphp
                            <tr class="border-bottom border-light">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        @if($leave->employee?->avatar)
                                            <img src="{{ asset('storage/'.$leave->employee->avatar) }}" 
                                                 class="rounded-circle me-3" width="36" height="36" 
                                                 alt="{{ $leave->employee->full_name }}">
                                        @else
                                            <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                                 style="width:36px;height:36px; background-color: rgba(93, 135, 255, 0.1)!important; color: #5d87ff!important;">
                                                {{ substr($leave->employee?->full_name ?? '?', 0, 1) }}
                                            </div>
                                        @endif
                                        <span class="fw-medium">{{ $leave->employee?->full_name ?? '---' }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @switch($leave->leave_type)
                                        @case('annual')
                                            <span class="badge px-3 py-2 rounded-pill" style="background-color: rgba(57, 203, 127, 0.1)!important; color: #39cb7f!important;">
                                                <i class="mdi mdi-palm-tree me-1"></i> سنوية
                                            </span>
                                            @break
                                        @case('casual')
                                            <span class="badge px-3 py-2 rounded-pill" style="background-color: rgba(93, 135, 255, 0.1)!important; color: #5d87ff!important;">
                                                <i class="mdi mdi-run-fast me-1"></i> عارضة
                                            </span>
                                            @break
                                        @case('sick')
                                            <span class="badge px-3 py-2 rounded-pill" style="background-color: rgba(252, 75, 108, 0.1)!important; color: #fc4b6c!important;">
                                                <i class="mdi mdi-hospital-box me-1"></i> مرضية
                                            </span>
                                            @break
                                        @default
                                            <span class="badge px-3 py-2 rounded-pill" style="background-color: rgba(111, 123, 138, 0.1)!important; color: #6f7b8a!important;">
                                                <i class="mdi mdi-help-circle me-1"></i> أخرى
                                            </span>
                                    @endswitch
                                </td>
                                <td class="text-center fw-medium">{{ $leave->start_date }}</td>
                                <td class="text-center fw-medium">{{ $leave->end_date }}</td>
                                <td class="text-center">
                                    @if($leave->reason)
                                        <button class="btn btn-sm btn-outline-secondary rounded-circle" 
                                                data-bs-toggle="tooltip" 
                                                title="{{ $leave->reason }}"
                                                style="width:32px;height:32px; border-color: #e0e0e0!important;">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($leave->status == 'pending')
                                        <span class="badge rounded-pill py-2 px-3" style="background-color: rgba(255, 171, 67, 0.1)!important; color: #ffab43!important;">
                                            <i class="mdi mdi-clock-outline me-1"></i> قيد المراجعة
                                        </span>
                                    @elseif($leave->status == 'approved')
                                        <span class="badge rounded-pill py-2 px-3" style="background-color: rgba(57, 203, 127, 0.1)!important; color: #39cb7f!important;">
                                            <i class="mdi mdi-check-circle-outline me-1"></i> موافق عليها
                                        </span>
                                    @else
                                        <span class="badge rounded-pill py-2 px-3" style="background-color: rgba(252, 75, 108, 0.1)!important; color: #fc4b6c!important;">
                                            <i class="mdi mdi-close-circle-outline me-1"></i> مرفوضة
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center fw-bold" style="color: #39cb7f!important;">{{ $allowed }}</td>
                                <td class="text-center fw-bold" style="color: #fc4b6c!important;">{{ $used }}</td>
                                <td class="text-center fw-bold pe-4" style="color: #5d87ff!important;">{{ $remaining }}</td>
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
        font-weight: 600;
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
        transition: all 0.2s ease;
    }
    
    .table th {
        white-space: nowrap;
        padding: 12px 8px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .table td {
        padding: 16px 8px;
        vertical-align: middle;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(93, 135, 255, 0.05)!important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .card {
        border-radius: 12px;
        overflow: hidden;
        border: none;
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(93, 135, 255, 0.3);
    }
    
    .btn-outline-secondary {
        border-color: #e0e0e0;
    }
    
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
    }
    
    body {
        background-color: #f8fafc;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تهيئة الأدوات المساعدة
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                placement: 'top',
                trigger: 'hover'
            });
        });
    });
</script>