<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="text-center" style="max-width: 500px; width: 100%;">
        <!-- رسائل التنبيه -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 shadow-sm">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="mdi mdi-check-circle-outline me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-3 shadow-sm">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="mdi mdi-alert-circle-outline me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- بطاقة تسجيل الحضور -->
        <div class="card shadow-sm border-0 rounded-3 p-4">
            <div class="card-body">
                <h4 class="mb-4">
                    <i class="mdi mdi-clock-check-outline me-2"></i>
                    تسجيل الحضور
                </h4>

                @if ($attendanceToday)
                    <!-- حالة تسجيل الحضور -->
                    <div class="text-start mb-4">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted">
                                <i class="mdi mdi-login me-2"></i> وقت الدخول:
                            </span>
                            <span class="fw-bold">
                                {{ \Carbon\Carbon::parse($attendanceToday->check_in)->format('H:i') }}
                            </span>
                        </div>

                        @if ($attendanceToday->check_out)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted">
                                    <i class="mdi mdi-logout me-2"></i> وقت الانصراف:
                                </span>
                                <span class="fw-bold">
                                    {{ \Carbon\Carbon::parse($attendanceToday->check_out)->format('H:i') }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="text-muted">
                                    <i class="mdi mdi-clock-outline me-2"></i> عدد الساعات:
                                </span>
                                <span class="fw-bold text-primary">
                                    @php
                                        $mins = (int) $attendanceToday->hours;
                                    @endphp
                                    {{ intdiv($mins, 60) }} ساعة {{ $mins % 60 }} دقيقة
                                </span>
                            </div>
                        @else
                            <div class="mt-4 pt-3">
                                <button type="button" wire:click="checkOut" class="btn btn-danger btn-lg rounded-pill px-5 py-3 shadow-sm w-100">
                                    <i class="mdi mdi-logout me-2"></i> تسجيل الانصراف
                                </button>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- حالة عدم تسجيل الحضور -->
                    <div class="my-4 py-2">
                        <i class="mdi mdi-login text-success" style="font-size: 3rem;"></i>
                    </div>
                    <button type="button" wire:click="checkIn" class="btn btn-success btn-lg rounded-pill px-5 py-3 shadow-sm w-100">
                        <i class="mdi mdi-login me-2"></i> تسجيل الحضور
                    </button>
                @endif

                <div class="mt-3 text-muted small">
                    <i class="mdi mdi-calendar-today me-1"></i>
                    {{ \Carbon\Carbon::now()->format('Y-m-d') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card { border: none; }
    .btn-lg { font-size: 1.1rem; }
    .rounded-3 { border-radius: 1rem; }
    .shadow-sm { box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075) !important; }
</style>
