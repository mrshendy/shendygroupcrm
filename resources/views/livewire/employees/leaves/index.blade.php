<div class="container-fluid p-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0 fw-bold">
                <i class="mdi mdi-beach me-2"></i>
                إدارة الإجازات
            </h5>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold"><i class="mdi mdi-account-outline me-1"></i> الموظف</th>
                            <th class="fw-bold"><i class="mdi mdi-format-list-bulleted-type me-1"></i> النوع</th>
                            <th class="fw-bold"><i class="mdi mdi-calendar-start me-1"></i> من</th>
                            <th class="fw-bold"><i class="mdi mdi-calendar-end me-1"></i> إلى</th>
                            <th class="fw-bold"><i class="mdi mdi-comment-text-outline me-1"></i> السبب</th>
                            <th class="fw-bold text-center"><i class="mdi mdi-clipboard-check-outline me-1"></i> الحالة</th>
                            <th class="fw-bold text-center"><i class="mdi mdi-check-circle-outline me-1"></i> المسموح</th>
                            <th class="fw-bold text-center"><i class="mdi mdi-alert-circle-outline me-1"></i> المستهلك</th>
                            <th class="fw-bold text-center"><i class="mdi mdi-timer-sand-empty me-1"></i> المتبقي</th>
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
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($leave->employee?->avatar)
                                            <img src="{{ asset('storage/'.$leave->employee->avatar) }}" 
                                                 class="rounded-circle me-2" width="32" height="32" 
                                                 alt="{{ $leave->employee->full_name }}">
                                        @else
                                            <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                                 style="width:32px;height:32px;">
                                                {{ substr($leave->employee?->full_name ?? '?', 0, 1) }}
                                            </div>
                                        @endif
                                        <span>{{ $leave->employee?->full_name ?? '---' }}</span>
                                    </div>
                                </td>
                                <td>
                                    @switch($leave->leave_type)
                                        @case('annual')
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                سنوية
                                            </span>
                                            @break
                                        @case('casual')
                                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                                عارضة
                                            </span>
                                            @break
                                        @case('sick')
                                            <span class="badge bg-danger bg-opacity-10 text-danger">
                                                مرضية
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                أخرى
                                            </span>
                                    @endswitch
                                </td>
                                <td>{{ $leave->start_date }}</td>
                                <td>{{ $leave->end_date }}</td>
                                <td>
                                    @if($leave->reason)
                                        <button class="btn btn-sm btn-outline-secondary" 
                                                data-bs-toggle="tooltip" 
                                                title="{{ $leave->reason }}">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </button>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($leave->status == 'pending')
                                        <span class="badge bg-warning rounded-pill py-2 px-3">
                                            قيد المراجعة
                                        </span>
                                    @elseif($leave->status == 'approved')
                                        <span class="badge bg-success rounded-pill py-2 px-3">
                                            موافق عليها
                                        </span>
                                    @else
                                        <span class="badge bg-danger rounded-pill py-2 px-3">
                                            مرفوضة
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ $allowed }}</td>
                                <td class="text-center fw-bold">{{ $used }}</td>
                                <td class="text-center fw-bold text-primary">{{ $remaining }}</td>
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
    }
    
    .table th {
        white-space: nowrap;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    .card {
        border-radius: 0.5rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تهيئة الأدوات المساعدة
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>