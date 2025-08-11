@extends('layouts.app')

@section('title', 'عرض العقد')

@section('content')
<div class="container" dir="rtl">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">عرض العقد #{{ $contract->id }}</h3>
        <div>
            <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-warning">تعديل (Livewire)</a>
            <a href="{{ route('contracts.index') }}" class="btn btn-secondary">رجوع</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">بيانات العقد</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm">
                <tbody>
                <tr>
                    <th style="width:200px">العميل</th><td>{{ $contract->client?->name }}</td>
                    <th>نوع العقد</th><td>{{ \App\Models\Contract::TYPES[$contract->type] ?? $contract->type }}</td>
                </tr>
                <tr>
                    <th>المشروع</th><td>{{ $contract->project?->name ?? '—' }}</td>
                    <th>العرض</th><td>{{ $contract->offer ? ('#'.$contract->offer->id) : '—' }}</td>
                </tr>
                <tr>
                    <th>الفترة</th>
                    <td>{{ optional($contract->start_date)->format('Y-m-d') }} — {{ optional($contract->end_date)->format('Y-m-d') }}</td>
                    <th>الحالة</th><td>{{ $contract->status ?? '—' }}</td>
                </tr>
                <tr>
                    <th>الإجمالي</th>
                    <td>{{ number_format($contract->amount,2) }} {{ $contract->include_tax ? '(شامل الضريبة)' : '' }}</td>
                    <th>ملف العقد</th>
                    <td>
                        @if($contract->contract_file)
                            <a href="{{ route('contracts.download', $contract) }}">تنزيل</a>
                        @else — @endif
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">بنود العقد</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm">
                <thead><tr><th>#</th><th>الترتيب</th><th>العنوان</th><th>النص</th></tr></thead>
                <tbody>
                @forelse($contract->items as $i=>$it)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $it->sort_order }}</td>
                        <td>{{ $it->title }}</td>
                        <td>{{ $it->body }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">لا توجد بنود</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">دفعات العقد</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                <tr>
                    <th>#</th><th>النوع</th><th>العنوان</th><th>المرحلة</th><th>شهر الفترة</th>
                    <th>شرط التحصيل</th><th>تاريخ الاستحقاق</th><th>المبلغ</th>
                    <th>شامل</th><th>مدفوعة؟</th><th>ملاحظات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($contract->payments as $i=>$p)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $p->payment_type }}</td>
                        <td>{{ $p->title }}</td>
                        <td>{{ $p->stage ? \App\Models\ContractPayment::STAGES[$p->stage] : '—' }}</td>
                        <td>{{ optional($p->period_month)->format('Y-m') }}</td>
                        <td>{{ $p->condition }}</td>
                        <td>{{ optional($p->due_date)->format('Y-m-d') }}</td>
                        <td>{{ number_format($p->amount,2) }}</td>
                        <td>{{ $p->include_tax ? '✓' : '✗' }}</td>
                        <td>{{ $p->is_paid ? '✓' : '✗' }}</td>
                        <td>{{ $p->notes }}</td>
                    </tr>
                @empty
                    <tr><td colspan="11" class="text-center text-muted">لا توجد دفعات</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
