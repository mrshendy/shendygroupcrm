<div class="container-fluid py-4">
    <!-- العنوان -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary mb-0">
            <i class="mdi mdi-calendar-check-outline me-2"></i>
            إدارة الحضور والانصراف
        </h4>
        <!-- اختيار تاريخ -->
        <div class="d-flex align-items-center">
            <input type="date" wire:model="filterDate" class="form-control form-control-sm shadow-sm">
        </div>
    </div>

    <!-- كارت -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-gradient-primary text-white rounded-top-4 py-3">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-account-clock me-2 fs-5"></i>
                <h6 class="mb-0">سجلات اليوم</h6>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><i class="mdi mdi-account-outline me-1 text-primary"></i> الموظف</th>
                            <th><i class="mdi mdi-clock-start me-1 text-success"></i> وقت الحضور</th>
                            <th><i class="mdi mdi-clock-end me-1 text-danger"></i> وقت الانصراف</th>
                            <th><i class="mdi mdi-timer-sand me-1 text-warning"></i> عدد الساعات</th>
                            <th><i class="mdi mdi-calendar me-1 text-info"></i> التاريخ</th>
                            <th class="text-center"><i class="mdi mdi-cog me-1 text-secondary"></i> إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr class="hover-row">
                                <td class="fw-semibold">{{ $attendance->employee->full_name }}</td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                        {{ $attendance->check_in ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">
                                        {{ $attendance->check_out ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">
                                        {{ $attendance->hours ?? '0' }}
                                    </span>
                                </td>
                                <td>{{ $attendance->attendance_date }}</td>
                                <td class="text-center">
                                    <a href="{{ route('attendance.attendanceedit', $attendance->id) }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                        <i class="mdi mdi-pencil-outline"></i> تعديل
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="mdi mdi-database-remove-outline fs-3 d-block mb-2"></i>
                                    لا يوجد حضور مسجل لليوم
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if ($attendances->hasPages())
            <div class="card-footer bg-light border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        عرض <strong>{{ $attendances->firstItem() }}</strong> إلى
                        <strong>{{ $attendances->lastItem() }}</strong> من
                        <strong>{{ $attendances->total() }}</strong> سجل
                    </div>
                    <div>
                        {{ $attendances->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .hover-row:hover {
        background-color: #f9fafb;
        transition: 0.3s;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #4f46e5, #3b82f6);
    }
</style>
