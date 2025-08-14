<div class="container mt-4">
    <h3>إدارة الحضور والانصراف</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>اسم الموظف</th>
                <th>وقت الحضور</th>
                <th>وقت الانصراف</th>
                <th>عدد الساعات</th>
                <th>التاريخ</th>
                <th>تعديل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->employee->full_name }}</td>
                    <td>{{ $attendance->check_in }}</td>
                    <td>{{ $attendance->check_out ?? '-' }}</td>
                    <td>{{ $attendance->hours ?? 0 }}</td>
                    <td>{{ $attendance->attendance_date }}</td>
                    <td>
 <a href="{{ route('attendance.attendanceedit', $attendance->id) }}" class="btn btn-warning btn-sm">
        <i class="mdi mdi-pencil"></i> تعديل
    </a>                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
