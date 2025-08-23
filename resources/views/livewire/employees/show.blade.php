<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بيانات موظف</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-ZOCkKoFj7cZ0ZJ1XpH5rjZ2E8yoxkzKmrJYqjWlFwbBejG6/7y6pzwI6B4Gv+ycFbhz7sjkXzOD1C7Fou5nkwQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #334155;
            font-size: 14px;
        }

        .page-header {
            background: linear-gradient(90deg, #60a5fa, #3b82f6);
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 18px;
        }

        .edit-btn {
            background: #fff;
            color: #3b82f6;
            border: 1px solid #93c5fd;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
        }

        .edit-btn:hover {
            background: #3b82f6;
            color: white;
        }

        .nav-tabs .nav-link {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            padding: 10px 16px;
        }

        .nav-tabs .nav-link.active {
            color: #3b82f6;
            border-bottom: 3px solid #3b82f6;
            background: none;
        }

        .tab-content {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 20px;
            margin-top: 15px;
        }

        .info-card {
            background: #f9fafb;
            border-radius: 10px;
            padding: 15px;
            border: 1px solid #e5e7eb;
            margin-bottom: 15px;
        }

        .info-card p {
            margin-bottom: 8px;
            font-size: 13px;
            display: flex;
            align-items: center;
        }

        .info-card strong {
            color: #3b82f6;
            min-width: 120px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .custom-table {
            font-size: 13px;
        }

        .custom-table thead {
            background: #f1f5f9;
            color: #475569;
        }

        .custom-table th, .custom-table td {
            padding: 10px 12px;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
            color: #f1f5f9;
        }

        .no-data {
            text-align: center;
            padding: 40px 15px;
            color: #94a3b8;
            font-size: 13px;
        }

        .no-data i {
            font-size: 32px;
            margin-bottom: 10px;
            opacity: 0.6;
        }

        .card-footer {
            background: #f1f5f9;
            font-size: 12px;
            color: #64748b;
            text-align: center;
            padding: 10px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-3">
        <!-- Header -->
        <div class="page-header">
            <h5><i class="fas fa-user me-2"></i>تفاصيل الموظف</h5>
            <a href="{{ route('employees.edit', $employee->id) }}" class="edit-btn">
                <i class="fas fa-edit me-1"></i> تعديل البيانات
            </a>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#personal"><i class="fas fa-id-card me-1"></i>المعلومات الشخصية</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#shift"><i class="fas fa-business-time me-1"></i>الشيفت</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#leaves"><i class="fas fa-umbrella-beach me-1"></i>الإجازات</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#attendance"><i class="fas fa-calendar-check me-1"></i>الحضور</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#salary"><i class="fas fa-money-bill me-1"></i>المرتب</a></li>
        </ul>

        <div class="tab-content">
            <!-- Personal -->
            <div class="tab-pane fade show active" id="personal">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-card">
                            <p><strong><i class="fas fa-user"></i> الاسم:</strong> {{ $employee->full_name }}</p>
                            <p><strong><i class="fas fa-id-badge"></i> الكود:</strong> {{ $employee->employee_code }}</p>
                            <p><strong><i class="fas fa-building"></i> القسم:</strong> {{ $employee->department }}</p>
                            <p><strong><i class="fas fa-briefcase"></i> الوظيفة:</strong> {{ $employee->job_title }}</p>
                            <p><strong><i class="fas fa-birthday-cake"></i> الميلاد:</strong> {{ $employee->birth_date }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <p><strong><i class="fas fa-envelope"></i> البريد:</strong> {{ $employee->email }}</p>
                            <p><strong><i class="fas fa-phone"></i> الهاتف:</strong> {{ $employee->phone }}</p>
                            <p><strong><i class="fas fa-map-marker-alt"></i> العنوان:</strong> {{ $employee->address }}</p>
                            <p><strong><i class="fas fa-venus-mars"></i> النوع:</strong> {{ $employee->gender }}</p>
                            <p><strong><i class="fas fa-circle"></i> الحالة:</strong>
                                <span class="status-badge bg-{{ $employee->status == 'مفعل' ? 'success' : 'danger' }}">{{ $employee->status }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shift -->
            <div class="tab-pane fade" id="shift">
                @if($employee->shift)
                    <div class="info-card">
                        <p><strong><i class="fas fa-signature"></i> الاسم:</strong> {{ $employee->shift->name }}</p>
                        <p><strong><i class="fas fa-calendar-week"></i> الأيام:</strong> {{ implode('، ', $employee->shift->days ?? []) }}</p>
                        <p><strong><i class="fas fa-clock"></i> الوقت:</strong> من {{ $employee->shift->start_time }} إلى {{ $employee->shift->end_time }}</p>
                        <p><strong><i class="fas fa-umbrella-beach"></i> رصيد الإجازات:</strong> {{ $employee->shift->leave_allowance }}</p>
                    </div>
                @else
                    <div class="no-data"><i class="fas fa-calendar-times"></i><p>لا يوجد شيفت محدد.</p></div>
                @endif
            </div>

            <!-- Leaves -->
            <div class="tab-pane fade" id="leaves">
                @if($employee->leaves->count())
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>النوع</th>
                                    <th>من</th>
                                    <th>إلى</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->leaves as $leave)
                                    <tr>
                                        <td>{{ $leave->leave_type }}</td>
                                        <td>{{ $leave->start_date }}</td>
                                        <td>{{ $leave->end_date }}</td>
                                        <td>
                                            <span class="status-badge bg-{{ $leave->status == 'approved' ? 'success' : ($leave->status == 'rejected' ? 'danger' : 'warning') }}">{{ $leave->status }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="no-data"><i class="fas fa-umbrella-beach"></i><p>لا توجد إجازات مسجلة.</p></div>
                @endif
            </div>

            <!-- Attendance -->
            <div class="tab-pane fade" id="attendance">
                @if($employee->attendances->count())
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>وقت الحضور</th>
                                    <th>وقت الانصراف</th>
                                    <th>عدد الساعات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->attendances as $att)
                                    <tr>
                                        <td>{{ $att->attendance_date }}</td>
                                        <td>{{ $att->check_in }}</td>
                                        <td>{{ $att->check_out ?? '-' }}</td>
                                        <td>{{ $att->hours ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="no-data"><i class="fas fa-calendar-times"></i><p>لا يوجد حضور مسجل.</p></div>
                @endif
            </div>

            <!-- Salary -->
            <div class="tab-pane fade" id="salary">
                @if($employee->salaries->count())
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>الشهر</th>
                                    <th>الراتب الأساسي</th>
                                    <th>البدلات</th>
                                    <th>الخصومات</th>
                                    <th>الصافي</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->salaries as $salary)
                                    <tr>
                                        <td>{{ $salary->month }}</td>
                                        <td>{{ number_format($salary->basic_salary,2) }}</td>
                                        <td>{{ number_format($salary->allowances,2) }}</td>
                                        <td>{{ number_format($salary->deductions,2) }}</td>
                                        <td class="fw-bold text-success">{{ number_format($salary->net_salary,2) }}</td>
                                        <td>
                                            <span class="status-badge bg-{{ $salary->status == 'approved' ? 'success' : ($salary->status == 'pending' ? 'warning' : 'danger') }}">{{ $salary->status }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="no-data"><i class="fas fa-money-bill-wave"></i><p>لا توجد بيانات مرتبات.</p></div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer">
            <i class="fas fa-sync-alt me-1"></i>
            آخر تحديث: {{ optional($employee->updated_at)->diffForHumans() ?? '---' }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
