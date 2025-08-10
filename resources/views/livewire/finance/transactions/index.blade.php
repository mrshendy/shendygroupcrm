<div class="card shadow-sm border-0 overflow-hidden" dir="rtl">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div class="d-flex align-items-center mb-3 mb-md-0">
                <h4 class="mb-0 ms-2">قائمة المصروفات والتحصيلات</h4>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('finance.transactions.create.expense') }}" class="btn btn-danger">إضافة مصروف</a>
                <a href="{{ route('finance.transactions.create.income') }}" class="btn btn-success">إضافة تحصيل</a>
            </div>
        </div>

        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="mb-3">
            <input type="text" class="form-control" placeholder="بحث بالملاحظات أو نوع التحصيل..."
                   wire:model.debounce.400ms="search">
        </div>

        <div class="table-responsive rounded-3 border">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th class="text-end">المبلغ</th>
                        <th>من</th>
                        <th>إلى</th>
                        <th>البند</th>
                        <th>التحصيل</th>
                        <th>ملاحظات</th>
                        <th>تاريخ الحركة</th>
                        <th>إنشاء</th>
                        <th class="text-center">إدارة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $i => $t)
                        @php $isExpense = $t->transaction_type === 'مصروفات'; @endphp
                        <tr>
                            <td>{{ $transactions->firstItem() + $i }}</td>
                            <td class="text-end fw-bold {{ $isExpense ? 'text-danger' : 'text-success' }}">
                                {{ number_format($t->amount,2) }}
                            </td>
                            <td>{{ optional($t->fromAccount)->name ?? '-' }}</td>
                            <td>{{ optional($t->toAccount)->name ?? '-' }}</td>
                            <td>{{ optional($t->item)->name ?? '-' }}</td>
                            <td>
                                @if(!$isExpense)
                                    <span class="badge bg-info-subtle text-info">
                                        {{ $t->collection_type ?? 'أخرى' }}
                                    </span>
                                    @if ($t->collection_type === 'تحصل من عميل')
                                        <div class="small text-muted">{{ optional($t->client)->name ?? '-' }}</div>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($t->notes)
                                    <span class="text-truncate d-inline-block" style="max-width:200px;" title="{{ $t->notes }}">
                                        {{ $t->notes }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($t->transaction_date)->format('Y-m-d') }}</td>
                            <td>{{ \Carbon\Carbon::parse($t->created_at)->format('Y-m-d H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('finance.transactions.edit', $t->id) }}" class="btn btn-sm btn-primary">تعديل</a>
                                <button wire:click="delete({{ $t->id }})" class="btn btn-sm btn-outline-danger">حذف</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center text-muted py-4">لا توجد بيانات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transactions->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="small text-muted">
                    عرض {{ $transactions->firstItem() }} إلى {{ $transactions->lastItem() }} من {{ $transactions->total() }}
                </div>
                <div>{{ $transactions->onEachSide(1)->links() }}</div>
            </div>
        @endif
    </div>
</div>
