<div class="card shadow-lg border-0 overflow-hidden" dir="rtl">
    <div class="card-body p-5">
        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5">
            <div class="d-flex align-items-center mb-4 mb-md-0">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="mdi mdi-cash-multiple text-primary" style="font-size: 1.5rem"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-gray-800">قائمة المصروفات والتحصيلات</h4>
                    <p class="text-muted mb-0">إدارة جميع المعاملات المالية</p>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-3">
                @can('finance-create.expense')
                <a href="{{ route('finance.transactions.create.expense') }}" class="btn btn-danger px-4 py-2 rounded-pill shadow-sm">
                    <i class="mdi mdi-cash-minus me-2"></i> إضافة مصروفات
                </a>
                @endcan
                @can('finance-create.income')
                <a href="{{ route('finance.transactions.create.income') }}" class="btn btn-success px-4 py-2 rounded-pill shadow-sm">
                    <i class="mdi mdi-cash-plus me-2"></i> إضافة إيراد
                </a>
                @endcan
            </div>
        </div>

        <!-- Flash Message -->
        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show mb-5 border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="mdi mdi-check-circle-outline me-2" style="font-size: 1.25rem"></i>
                    <div>{{ session('message') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <!-- Search -->
        <div class="row g-3 mb-5">
            <div class="col-md-6">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="mdi mdi-magnify text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" placeholder="بحث بالملاحظات أو نوع تحصيل..."
                        wire:model.debounce.400ms="search">
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="table-responsive rounded-4 border shadow-sm">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center ps-4" style="width: 60px">#</th>
                        <th class="text-end" style="min-width: 120px">المبلغ</th>
                        <th style="min-width: 150px">من</th>
                        <th style="min-width: 150px">إلى</th>
                        <th style="min-width: 150px">البند</th>
                        <th style="min-width: 150px">تحصيل</th>
                        <th style="min-width: 120px">التاريخ</th>
                        <th class="text-center pe-4" style="width: 220px">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $i => $t)
                        @php $isExpense = $t->type === 'مصروفات'; @endphp
                        <tr>
                            <td class="text-center ps-4 text-muted">{{ $transactions->firstItem() + $i }}</td>
                            <td class="text-end">
                                <div class="d-flex align-items-center justify-content-end">
                                    <i class="mdi mdi-{{ $isExpense ? 'arrow-up' : 'arrow-down' }}-circle-outline me-2 
                                        {{ $isExpense ? 'text-danger' : 'text-success' }}" style="font-size: 1.25rem"></i>
                                    <span class="fw-bold {{ $isExpense ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($t->amount, 2) }} ج.م
                                    </span>
                                </div>
                            </td>
                            <td>{{ optional($t->fromAccount)->name ?? '-' }}</td>
                            <td>{{ optional($t->toAccount)->name ?? '-' }}</td>
                            <td>{{ optional($t->item)->name ?? '-' }}</td>
                            <td>
                                @if (!$isExpense)
                                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                                        {{ $t->collection_type ?? 'أخرى' }}
                                    </span>
                                    @if ($t->collection_type === 'تحصل من عميل')
                                        <div class="small text-muted mt-1">{{ optional($t->client)->name ?? '-' }}</div>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($t->transaction_date)->format('Y-m-d') }}</td>
                            <td class="text-center pe-4">
                                <div class="d-flex gap-2 justify-content-center">
                                    @can('finance-view')
                                    <a href="{{ route('finance.transactions.show', $t->id) }}" class="btn btn-sm btn-outline-info px-3 rounded-pill">
                                        <i class="mdi mdi-eye-outline me-1"></i> عرض
                                    </a>
                                    @endcan
                                    @can('finance-edit')
                                    <a href="{{ route('finance.transactions.edit', $t->id) }}" class="btn btn-sm btn-primary px-3 rounded-pill">
                                        <i class="mdi mdi-pencil-outline me-1"></i> تعديل
                                    </a>
                                    @endcan
                                    @can('finance-delete')
                                    <button wire:click="confirmDelete({{ $t->id }})"
                                        class="btn btn-sm btn-outline-danger px-3 rounded-pill">
                                        <i class="mdi mdi-delete-outline me-1"></i> حذف
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">لا توجد معاملات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($transactions->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    عرض {{ $transactions->firstItem() }} إلى {{ $transactions->lastItem() }} من
                    {{ $transactions->total() }} معاملة
                </div>
                <div>{{ $transactions->onEachSide(1)->links() }}</div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">
                    <i class="mdi mdi-alert-circle-outline me-2"></i> تأكيد الحذف
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من رغبتك في حذف هذه الحركة المالية؟</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" wire:click="deleteConfirmed">
                    <i class="mdi mdi-delete-outline me-1"></i> نعم، احذف
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('showDeleteModal', () => {
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    });
    window.addEventListener('hideDeleteModal', () => {
        var deleteModalEl = document.getElementById('deleteModal');
        var modal = bootstrap.Modal.getInstance(deleteModalEl);
        if (modal) modal.hide();
    });
</script>
@endpush
