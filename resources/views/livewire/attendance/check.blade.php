<div class="text-center mt-5">
    @if(!$attendanceToday)
        <button wire:click="checkIn" class="btn btn-success btn-lg">
            تسجيل الحضور
        </button>
    @elseif(!$attendanceToday->check_out)
        <button wire:click="checkOut" class="btn btn-danger btn-lg">
            تسجيل الانصراف
        </button>
    @else
        <div class="alert alert-info">
            تم تسجيل الحضور والانصراف لليوم.
        </div>
    @endif
</div>
