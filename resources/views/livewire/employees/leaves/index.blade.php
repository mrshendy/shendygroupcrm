<div>
    @if($leaves->count() > 0)
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>الموظف</th>
                    <th>نوع الإجازة</th>
                    <th>من</th>
                    <th>إلى</th>
                    <th>السبب</th>
                    <th>الحالة</th>
                    <th>تاريخ التقديم</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaves as $leave)
                    <tr>
                        <td>{{ $leave->employee?->full_name ?? '---' }}</td>
                        <td>
                            @if($leave->leave_type == 'annual') سنوية
                            @elseif($leave->leave_type == 'sick') مرضية
                            @elseif($leave->leave_type == 'unpaid') بدون مرتب
                            @else أخرى
                            @endif
                        </td>
                        <td>{{ $leave->start_date }}</td>
                        <td>{{ $leave->end_date }}</td>
                        <td>{{ $leave->reason ?? '-' }}</td>
                        <td>
                            @if($leave->status == 'pending')
                                <span class="badge bg-warning">قيد المراجعة</span>
                            @elseif($leave->status == 'approved')
                                <span class="badge bg-success">مقبولة</span>
                            @else
                                <span class="badge bg-danger">مرفوضة</span>
                            @endif
                        </td>
                        <td>{{ $leave->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">لا توجد إجازات مسجلة</p>
    @endif
</div>
