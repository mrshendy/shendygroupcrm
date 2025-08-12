<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض العملية المالية</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Lottie Animations -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        .breadcrumb-item a {
            transition: all 0.3s ease;
        }
        .breadcrumb-item a:hover {
            color: #4f46e5;
        }
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .info-label {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        
        }
        .transaction-type {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .transaction-income {
            background-color: #ecfdf5;
            color: #059669;
        }
        .transaction-expense {
            background-color: #fee2e2;
            color: #dc2626;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container-fluid px-4 py-4" data-aos="fade-in">
        <!-- Breadcrumb / Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <nav aria-label="breadcrumb" class="flex items-center">
                <ol class="breadcrumb mb-0 flex items-center space-x-2 space-x-reverse">
                    <li class="breadcrumb-item">
                        <a href="{{ route('finance.transactions.index') }}" class="text-gray-500 hover:text-indigo-600 transition-colors">
                            <i class="mdi mdi-home mdi-20px"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('finance.transactions.index') }}" class="text-gray-500 hover:text-indigo-600 transition-colors">
                            المعاملات المالية
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-gray-700" aria-current="page">
                        عرض العملية #{{ $transaction->id }}
                    </li>
                </ol>
            </nav>

            <div class="d-flex gap-3">
                <a href="{{ route('finance.transactions.edit', $transaction->id) }}" 
                   class="btn btn-primary rounded-full px-4 py-2 flex items-center gap-2 shadow-md hover:shadow-lg transition-shadow">
                    <i class="mdi mdi-pencil"></i> 
                    <span>تعديل</span>
                </a>
                <a href="{{ route('finance.transactions.index') }}" 
                   class="btn btn-light rounded-full px-4 py-2 flex items-center gap-2 hover:bg-gray-100 transition-colors">
                    <i class="mdi mdi-arrow-right"></i> 
                    <span>رجوع</span>
                </a>
            </div>
        </div>

        <!-- Transaction Card -->
        <div class="card shadow-sm rounded-xl overflow-hidden bg-white" data-aos="zoom-in">
            <div class="card-header bg-white px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h5 class="mb-0 text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_80nu1g6r.json" background="transparent" speed="1" style="width: 32px; height: 32px" loop autoplay></lottie-player>
                        تفاصيل العملية المالية
                    </h5>
                    <span class="transaction-type {{ $transaction->type === 'income' ? 'transaction-income' : 'transaction-expense' }}">
                        <i class="mdi mdi-{{ $transaction->type === 'income' ? 'arrow-down' : 'arrow-up' }}-circle-outline me-1"></i>
                        {{ $transaction->type === 'income' ? 'إيرادات' : 'مصروفات' }}
                    </span>
                </div>
            </div>

            <div class="card-body p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Transaction ID -->
                    <div class="bg-gray-50 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="100">
                        <div class="info-label">رقم العملية</div>
                        <div class="info-value flex items-center gap-2">
                            <i class="mdi mdi-identifier text-indigo-500"></i>
                            {{ $transaction->id }}
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="bg-gray-50 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="150">
                        <div class="info-label">القيمة</div>
                        <div class="info-value flex items-center gap-2">
                            <i class="mdi mdi-currency-usd text-green-500"></i>
                            {{ number_format($transaction->amount, 2) }} ر.س
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="bg-gray-50 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="200">
                        <div class="info-label">تاريخ العملية</div>
                        <div class="info-value flex items-center gap-2">
                            <i class="mdi mdi-calendar-clock text-blue-500"></i>
                            {{ $transaction->transaction_date }}
                        </div>
                    </div>

                    <!-- From Account -->
                    <div class="bg-gray-50 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="250">
                        <div class="info-label">من الحساب</div>
                        <div class="info-value flex items-center gap-2">
                            <i class="mdi mdi-bank-outline text-purple-500"></i>
                            {{ $transaction->fromAccount->name ?? '—' }}
                        </div>
                    </div>

                    <!-- To Account -->
                    <div class="bg-gray-50 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="300">
                        <div class="info-label">إلى الحساب</div>
                        <div class="info-value flex items-center gap-2">
                            <i class="mdi mdi-bank-transfer text-amber-500"></i>
                            {{ $transaction->toAccount->name ?? '—' }}
                        </div>
                    </div>

                    <!-- Item -->
                    <div class="bg-gray-50 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="350">
                        <div class="info-label">البند</div>
                        <div class="info-value flex items-center gap-2">
                            <i class="mdi mdi-tag-outline text-emerald-500"></i>
                            {{ $transaction->item->name ?? '—' }}
                        </div>
                    </div>

                    <!-- Client -->
                    <div class="bg-gray-50 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="400">
                        <div class="info-label">العميل</div>
                        <div class="info-value flex items-center gap-2">
                            <i class="mdi mdi-account-outline text-cyan-500"></i>
                            {{ $transaction->client->name ?? '—' }}
                        </div>
                    </div>

                    @if(!empty($transaction->collection_type))
                        <!-- Collection Type -->
                        <div class="bg-gray-50 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="450">
                            <div class="info-label">نوع التحصيل</div>
                            <div class="info-value flex items-center gap-2">
                                <i class="mdi mdi-cash-multiple text-lime-500"></i>
                                {{ $transaction->collection_type }}
                            </div>
                        </div>
                    @endif

                    <!-- Added By -->
                    <div class="bg-gray-50 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="500">
                        <div class="info-label">أضيف بواسطة</div>
                        <div class="info-value flex items-center gap-2">
                            <i class="mdi mdi-account-plus-outline text-pink-500"></i>
                            {{ optional($transaction->addedBy)->name ?? '—' }}
                        </div>
                    </div>

                    <!-- Last Updated -->
                    <div class="bg-gray-50 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="550">
                        <div class="info-label">آخر تعديل</div>
                        <div class="info-value flex items-center gap-2">
                            <i class="mdi mdi-update text-orange-500"></i>
                            {{ optional($transaction->updated_at)->format('Y-m-d H:i') ?? '—' }}
                            @if(!empty($transaction->updatedBy) && !empty($transaction->updatedBy->name))
                                — {{ $transaction->updatedBy->name }}
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="info-label">الوصف</div>
                    <div class="info-value p-4 min-h-[100px]">
                        @if($transaction->notes)
                            <div class="prose max-w-none">
                                {!! nl2br(e($transaction->notes)) !!}
                            </div>
                        @else
                            <div class="text-gray-400 flex items-center justify-center h-full">
                                <i class="mdi mdi-note-off-outline me-2"></i>
                                لا يوجد وصف
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>
</body>
</html>