<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الحضور والانصراف</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');
        @import url('https://cdn.jsdelivr.net/npm/@mdi/font@6.9.96/css/materialdesignicons.min.css');
        
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --text-color: #2b2d42;
            --light-gray: #f8f9fa;
            --white: #ffffff;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f5f7fa;
            color: var(--text-color);
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .header h3 {
            color: var(--primary-color);
            font-size: 28px;
            font-weight: 700;
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
        }
        
        .header h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--accent-color);
            border-radius: 3px;
        }
        
        .attendance-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }
        
        .attendance-table thead {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white);
        }
        
        .attendance-table th {
            padding: 16px 20px;
            text-align: right;
            font-weight: 500;
            position: relative;
        }
        
        .attendance-table th i {
            margin-left: 8px;
            font-size: 18px;
            vertical-align: middle;
        }
        
        .attendance-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #e9ecef;
        }
        
        .attendance-table tbody tr:last-child {
            border-bottom: none;
        }
        
        .attendance-table tbody tr:hover {
            background-color: rgba(72, 149, 239, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .attendance-table td {
            padding: 14px 20px;
            text-align: right;
            border-bottom: 1px solid #e9ecef;
        }
        
        .attendance-table td:first-child {
            font-weight: 500;
            color: var(--primary-color);
        }
        
        .edit-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            background-color: var(--warning-color);
            color: var(--white);
            border: none;
            border-radius: var(--border-radius);
            font-family: 'Tajawal', sans-serif;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .edit-btn i {
            margin-left: 6px;
        }
        
        .edit-btn:hover {
            background-color: #e07d0e;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .attendance-table {
                display: block;
                overflow-x: auto;
            }
            
            .header h3 {
                font-size: 24px;
            }
            
            .attendance-table th, 
            .attendance-table td {
                padding: 12px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>إدارة الحضور والانصراف</h3>
        </div>
        
        <table class="attendance-table">
            <thead>
                <tr>
                    <th><i class="mdi mdi-account-outline"></i>اسم الموظف</th>
                    <th><i class="mdi mdi-clock-start"></i>وقت الحضور</th>
                    <th><i class="mdi mdi-clock-end"></i>وقت الانصراف</th>
                    <th><i class="mdi mdi-timer-sand"></i>عدد الساعات</th>
                    <th><i class="mdi mdi-calendar"></i>التاريخ</th>
                    <th><i class="mdi mdi-cog"></i>تعديل</th>
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
                            <a href="{{ route('attendance.attendanceedit', $attendance->id) }}" class="edit-btn">
                                <i class="mdi mdi-pencil"></i> تعديل
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>