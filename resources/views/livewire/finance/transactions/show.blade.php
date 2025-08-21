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

    <style>
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
    <div class="container mx-auto px-4 py-6" data-aos="fade-in">
        
        <!-- Breadcrumb -->
        <div class="flex justify-between items-center mb-6">
            <nav aria-label="breadcrumb">
                <ol class="flex items-center space-x-2 space-x-reverse text-sm">
                    <li>
                        <a href="{{ route('finance.transactions.index') }}" class="text-gray-500 hover:text-indigo-600">
                            <i class="mdi mdi-home mdi-20px"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('finance.transactions.index') }}" class="text-gray-500 hover:text-indigo-600">
                            المعاملات المالية
                        </a>
                    </li>
                    <li class="text-gray-700">
                        عرض العملية #{{ $transaction->id }}
                    </li>
                </ol>
            </nav>

            <div class="flex gap-3">
                <a href="{{ route('finance.transactions.edit', $transaction->id) }}" 
                   class="bg-indigo-600 text-white px-4 py-2 rounded-full flex items-center gap-2 shadow hover:bg-indigo-700 transition">
                    <i class="mdi mdi-pencil"></i> تعديل
                </a>
                <a href="{{ route('finance.transactions.index') }}" 
                   class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full flex items-center gap-2 hover:bg-gray-200 transition">
                    <i class="mdi mdi-arrow-right"></i> رجوع
                </a>
            </div>
        </div>

        <!-- Transaction Card -->
        <div class="bg-white rounded-xl shadow p-6" data-aos="zoom-in">
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h5 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                    <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_80nu1g6r.json" background="transparent" speed="1" style="width: 32px; height: 32px" loop autoplay></lottie-player>
                    تفاصيل العملية المالية
                </h5>
                <span class="transaction-type {{ $transaction->type === 'income' ? 'transaction-income' : 'transaction-expense' }}">
                    <i class="mdi mdi-{{ $transaction->type === 'income' ? 'arrow-down' : 'arrow-up' }}-circle-outline me-1"></i>
                    {{ $transaction->type === 'income' ? 'إيرادات' : 'مصروفات' }}
                </span>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <x-finance.info label="رقم العملية" icon="mdi-identifier text-indigo-500">
                    {{ $transaction->id }}
                </x-finance.info>

                <x-finance.info label="القيمة" icon="mdi-currency-usd text-green-500">
                    {{ number_format($transaction->amount, 2) }} ج.م
                </x-finance.info>

                <x-finance.info label="تاريخ العملية" icon="mdi-calendar-clock text-blue-500">
                    {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d') }}
                </x-finance.info>

                <x-finance.info label="من الحساب" icon="mdi-bank-outline text-purple-500">
                    {{ $transaction->fromAccount->name ?? '—' }}
                </x-finance.info>

                <x-finance.info label="إلى الحساب" icon="mdi-bank-transfer text-amber-500">
                    {{ $transaction->toAccount->name ?? '—' }}
                </x-finance.info>

                <x-finance.info label="البند" icon="mdi-tag-outline text-emerald-500">
                    {{ $transaction->item->name ?? '—' }}
                </x-finance.info>

                <x-finance.info label="العميل" icon="mdi-account-outline text-cyan-500">
                    {{ $transaction->client->name ?? '—' }}
                </x-finance.info>

                @if($transaction->collection_type)
                <x-finance.info label="نوع التحصيل" icon="mdi-cash-multiple text-lime-500">
                    {{ $transaction->collection_type }}
                </x-finance.info>
                @endif

                <x-finance.info label="أضيف بواسطة" icon="mdi-account-plus-outline text-pink-500">
                    {{ optional($transaction->addedBy)->name ?? '—' }}
                </x-finance.info>

                <x-finance.info label="آخر تعديل" icon="mdi-update text-orange-500">
                    {{ optional($transaction->updated_at)->format('Y-m-d H:i') ?? '—' }}
                    @if(!empty($transaction->updatedBy?->name))
                        — {{ $transaction->updatedBy->name }}
                    @endif
                </x-finance.info>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <div class="text-gray-600 text-sm mb-2">الوصف</div>
                <div class="bg-gray-50 p-4 rounded-lg min-h-[100px]">
                    @if($transaction->notes)
                        {!! nl2br(e($transaction->notes)) !!}
                    @else
                        <div class="text-gray-400 flex items-center justify-center h-full">
                            <i class="mdi mdi-note-off-outline me-2"></i> لا يوجد وصف
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- AOS Init -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, easing: 'ease-in-out', once: true });
    </script>
</body>
</html>

                