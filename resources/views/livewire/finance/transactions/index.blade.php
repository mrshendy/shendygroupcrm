<div class="card shadow-sm border-0 overflow-hidden">
    <div class="card-body p-4">
        <!-- Card Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div class="d-flex align-items-center mb-3 mb-md-0">
                <lord-icon src="{{ URL::asset('assets/images/icon/finance-transactions.json') }}" trigger="loop"
                    colors="primary:#4b38b3,secondary:#08a88a" style="width:50px;height:50px">
                </lord-icon>
                <h4 class="mb-0 ms-2">قائمة المصروفات والتحصيلات</h4>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('finance.transactions.create.expense') }}" class="btn btn-danger px-4 py-2">
                    <i class="mdi mdi-minus-circle-outline me-2"></i>
                    إضافة مصروف
                </a>
                <a href="{{ route('finance.transactions.create.income') }}" class="btn btn-success px-4 py-2">
                    <i class="mdi mdi-plus-circle-outline me-2"></i>
                    إضافة تحصيل
                </a>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="table-responsive rounded-3 border">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 60px;">#</th>
                        <th style="width: 100px; padding-left: 1rem; padding-right: 1rem;">رقم الحركة</th>
                        <th style="width: 120px; padding-left: 1rem; padding-right: 1rem;" class="text-end">المبلغ</th>
                        <th style="padding-left: 1rem; padding-right: 1rem;">المستلم</th>
                        <th style="width: 120px; padding-left: 1rem; padding-right: 1rem;">النوع</th>
                        <th style="padding-left: 1rem; padding-right: 1rem;">البند</th>
                        <th style="padding-left: 1rem; padding-right: 1rem;">نوع التحصيل</th>
                        <th style="width: 200px; padding-left: 1rem; padding-right: 1rem;">ملاحظات</th>
                        <th style="width: 120px; padding-left: 1rem; padding-right: 1rem;">تاريخ الحركة</th>
                        <th style="width: 150px; padding-left: 1rem; padding-right: 1rem;">تاريخ الإنشاء</th>
                        <th style="width: 100px; padding-left: 1rem; padding-right: 1rem;" class="text-center">إدارة
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($transactions as $index => $t)
                        @php
                            $isExpense = $t->transaction_type === 'مصروفات';
                            $badgeTone = $isExpense ? 'danger' : 'success';
                        @endphp
                        <tr wire:key="transaction-{{ $t->id }}">
                            <td class="ps-3">{{ $transactions->firstItem() + $index }}</td>
                            <td class="px-3 fw-semibold">#{{ $t->id }}</td>

                            <td class="px-3 text-end fw-bold {{ $isExpense ? 'text-danger' : 'text-success' }}">
                                {{ number_format($t->amount, 2) }}
                            </td>

                            <td class="px-3">
                                <div class="d-flex align-items-center">
                                    <span class="mdi mdi-account-circle-outline me-2 text-muted"></span>
                                    {{ optional($t->account)->name ?? '-' }}
                                </div>
                            </td>

                            <td class="px-3">
                                <span class="badge bg-{{ $badgeTone }}-subtle text-{{ $badgeTone }}">
                                    {{ $t->transaction_type }}
                                </span>
                            </td>

                            <td class="px-3">
                                <div class="d-flex align-items-center">
                                    <span class="mdi mdi-tag-outline me-2 text-muted"></span>
                                    {{ optional($t->item)->name ?? '-' }}
                                </div>
                            </td>

                            <td class="px-3">
                                @php $isExpense = ($t->transaction_type === 'مصروفات'); @endphp
                                @if (!$isExpense)
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



                            <td class="px-3">
                                @if ($t->notes)
                                    <span class="text-truncate d-inline-block" style="max-width:200px;"
                                        title="{{ $t->notes }}">
                                        <span
                                            class="mdi mdi-note-text-outline me-1 text-muted"></span>{{ $t->notes }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td class="px-3">{{ \Carbon\Carbon::parse($t->transaction_date)->format('Y-m-d') }}</td>
                            <td class="px-3">{{ \Carbon\Carbon::parse($t->created_at)->format('Y-m-d H:i') }}</td>

                            <td class="px-3 text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        <i class="mdi mdi-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item text-danger"
                                                wire:click.prevent="delete({{ $t->id }})">
                                                <i class="mdi mdi-delete-outline me-1"></i> إلغاء الحركة
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-5 text-muted">
                                <lord-icon src="{{ URL::asset('assets/images/icon/empty-data.json') }}" trigger="loop"
                                    colors="primary:#ccc,secondary:#eee" style="width:100px;height:100px"></lord-icon>
                                <p class="mt-3">لا توجد حركات مسجلة حالياً</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>


            </table>
        </div>

        <!-- Pagination -->
        @if ($transactions->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    <span class="mdi mdi-counter me-1"></span>
                    عرض <span class="fw-semibold">{{ $transactions->firstItem() }}</span> إلى
                    <span class="fw-semibold">{{ $transactions->lastItem() }}</span> من
                    <span class="fw-semibold">{{ $transactions->total() }}</span> نتيجة
                </div>
                <div>
                    {{ $transactions->onEachSide(1)->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
