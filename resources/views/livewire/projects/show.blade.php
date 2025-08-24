<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل المشروع</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- Google Fonts - Tajawal -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #3a7bd5;
            --secondary: #00d2ff;
            --success: #2ecc71;
            --warning: #f39c12;
            --danger: #e74c3c;
            --dark: #2c3e50;
            --light: #f8f9fa;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #333;
            min-height: 100vh;
            padding: 15px 0;
            font-size: 0.9rem;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.4);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            border-bottom: none;
            padding: 1rem 1.25rem;
        }
        
        .info-card {
            background: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
            padding: 1rem;
            height: 100%;
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        
        .section-title {
            position: relative;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }
        
        .section-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            right: 0;
            width: 35px;
            height: 2px;
            border-radius: 2px;
            background: currentColor;
        }
        
        .info-item {
            margin-bottom: 0.6rem;
            padding-bottom: 0.6rem;
            border-bottom: 1px dashed #eee;
            font-size: 0.85rem;
        }
        
        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .badge-custom {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.75rem;
        }
        
        .priority-low {background: #d4edda; color: #155724;}
        .priority-medium {background: #fff3cd; color: #856404;}
        .priority-high {background: #f8d7da; color: #721c24;}
        .priority-critical {background: #721c24; color: #fff;}
        
        .status-new {background: #e2e3e5; color: #383d41;}
        .status-in_progress {background: #cce5ff; color: #004085;}
        .status-completed {background: #d4edda; color: #155724;}
        .status-closed {background: #d6d8db; color: #1b1e21;}
        
        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            padding: 0.35rem 1rem;
            font-weight: 500;
            font-size: 0.85rem;
            color: white;
        }
        
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-3px);
        }
        
        .icon-wrapper {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 8px;
            background: rgba(255, 255, 255, 0.25);
            font-size: 1rem;
        }
        
        .card-footer {
            background: rgba(255, 255, 255, 0.7);
            border-top: 1px solid rgba(255, 255, 255, 0.5);
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="container py-3">
        <div class="glass-card">
            <!-- Header -->
            <div class="card-header text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper">
                        <i class="mdi mdi-folder-information-outline"></i>
                    </div>
                    <h5 class="mb-0 fw-bold">تفاصيل المشروع: {{ $project->name }}</h5>
                </div>
                <a href="{{ route('projects.index') }}" class="btn btn-back">
                    <i class="mdi mdi-arrow-right"></i> رجوع
                </a>
            </div>

            <!-- Body -->
            <div class="card-body p-3">
                <div class="row g-3">
                    <!-- Basic Info -->
                    <div class="col-md-6">
                        <div class="info-card">
                            <h6 class="section-title text-primary">
                                <i class="mdi mdi-information-outline me-2"></i>المعلومات الأساسية
                            </h6>
                            <div class="info-item"><strong>اسم المشروع:</strong> {{ $project->name }}</div>
                            <div class="info-item"><strong>الوصف المختصر:</strong> {{ $project->description ?? '-' }}</div>
                            <div class="info-item"><strong>التفاصيل:</strong> {{ $project->details ?? '-' }}</div>
                            <div class="info-item"><strong>نوع المشروع:</strong> {{ $project->project_type ?? '-' }}</div>
                            @if($project->project_type === 'programming')
                                <div class="info-item"><strong>نوع البرمجة:</strong> {{ $project->programming_type ?? '-' }}</div>
                                <div class="info-item"><strong>المرحلة الحالية:</strong> {{ $project->phase ?? '-' }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Relations -->
                    <div class="col-md-6">
                        <div class="info-card">
                            <h6 class="section-title text-success">
                                <i class="mdi mdi-account-multiple-outline me-2"></i>العلاقات
                            </h6>
                            <div class="info-item"><strong>العميل:</strong> {{ $project->client->name ?? '-' }}</div>
                            <div class="info-item"><strong>الدولة:</strong> {{ $project->country->name ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="col-md-6">
                        <div class="info-card">
                            <h6 class="section-title text-warning">
                                <i class="mdi mdi-calendar-clock me-2"></i>الجدول الزمني
                            </h6>
                            <div class="info-item"><strong>تاريخ البدء:</strong> {{ $project->start_date?->format('Y-m-d') ?? '-' }}</div>
                            <div class="info-item"><strong>تاريخ الانتهاء:</strong> {{ $project->end_date?->format('Y-m-d') ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <div class="info-card">
                            <h6 class="section-title text-danger">
                                <i class="mdi mdi-state-machine me-2"></i>الحالة والأولوية
                            </h6>
                            <div class="info-item">
                                <strong>الأولوية:</strong>
                                @php
                                    $priorityLabels = [
                                        'low' => ['text' => 'منخفضة', 'class' => 'priority-low'],
                                        'medium' => ['text' => 'متوسطة', 'class' => 'priority-medium'],
                                        'high' => ['text' => 'عالية', 'class' => 'priority-high'],
                                        'critical' => ['text' => 'حرجة', 'class' => 'priority-critical'],
                                    ];
                                    $priority = $priorityLabels[$project->priority] ?? ['text' => $project->priority, 'class' => ''];
                                @endphp
                                <span class="badge-custom {{ $priority['class'] }}">{{ $priority['text'] }}</span>
                            </div>
                            <div class="info-item">
                                <strong>الحالة:</strong>
                                @php
                                    $statusLabels = [
                                        'new' => ['text' => 'جديد', 'class' => 'status-new'],
                                        'in_progress' => ['text' => 'جاري العمل', 'class' => 'status-in_progress'],
                                        'completed' => ['text' => 'مكتمل', 'class' => 'status-completed'],
                                        'closed' => ['text' => 'مغلق', 'class' => 'status-closed'],
                                    ];
                                    $status = $statusLabels[$project->status] ?? ['text' => $project->status, 'class' => ''];
                                @endphp
                                <span class="badge-custom {{ $status['class'] }}">{{ $status['text'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="card-footer text-center">
                <small class="text-muted">
                    <i class="mdi mdi-calendar me-1"></i>
                    تم الإنشاء في: {{ $project->created_at?->format('Y-m-d H:i') }} |
                    آخر تحديث: {{ $project->updated_at?->format('Y-m-d H:i') }}
                </small>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
