<div>
    <!-- رسائل التنبيه -->
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- لو لم يتم تسجيل الحضور -->
    @if (!$attendanceToday)
        <button wire:click="checkIn" class="btn btn-success">
            <i class="mdi mdi-login"></i> تسجيل الحضور
        </button>
    @else
        <!-- عرض وقت الدخول -->
        <p><strong>وقت الدخول:</strong> {{ \Carbon\Carbon::parse($attendanceToday->check_in)->format('H:i') }}</p>

        @if ($attendanceToday->check_out)
            <!-- عرض وقت الانصراف -->
            <p><strong>وقت الانصراف:</strong> {{ \Carbon\Carbon::parse($attendanceToday->check_out)->format('H:i') }}</p>
            <p><strong>عدد الساعات:</strong> {{ $attendanceToday->hours }} ساعة</p>
        @else
            <!-- زر تسجيل الانصراف -->
            <button wire:click="checkOut" class="btn btn-danger">
                <i class="mdi mdi-logout"></i> تسجيل الانصراف
            </button>
        @endif
    @endif
</div>
