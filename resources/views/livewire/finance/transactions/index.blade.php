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
                <a href="{{ route('finance.transactions.create.expense') }}" class="btn btn-danger px-4 py-2 rounded-pill shadow-sm">
                    <i class="mdi mdi-cash-minus me-2"></i> إضافة مصروفات
                </a>
                <a href="{{ route('finance.transactions.create.income') }}" class="btn btn-success px-4 py-2 rounded-pill shadow-sm">
                    <i class="mdi mdi-cash-plus me-2"></i> إضافة إيراد
                </a>
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

        <!-- Search and Filters -->
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
                        <tr class="position-relative">
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
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-bank-outline text-muted me-2"></i>
                                    {{ optional($t->fromAccount)->name ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-bank-outline text-muted me-2"></i>
                                    {{ optional($t->toAccount)->name ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-tag-outline text-muted me-2"></i>
                                    {{ optional($t->item)->name ?? '-' }}
                                </div>
                            </td>
                            <td>
                                @if (!$isExpense)
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                                            <i class="mdi mdi-{{ $t->collection_type === 'تحصل من عميل' ? 'account' : 'cash' }}-outline me-1"></i>
                                            {{ $t->collection_type ?? 'أخرى' }}
                                        </span>
                                    </div>
                                    @if ($t->collection_type === 'تحصل من عميل')
                                        <div class="small text-muted mt-1">
                                            <i class="mdi mdi-account-outline me-1"></i>
                                            {{ optional($t->client)->name ?? '-' }}
                                        </div>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-calendar-blank-outline text-muted me-2"></i>
                                    {{ \Carbon\Carbon::parse($t->transaction_date)->format('Y-m-d') }}
                                </div>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('finance.transactions.show', $t->id) }}"
                                        class="btn btn-sm btn-outline-info px-3 rounded-pill"
                                        data-bs-toggle="tooltip" title="عرض التفاصيل">
                                        <i class="mdi mdi-eye-outline me-1"></i> عرض
                                    </a>
                                    <a href="{{ route('finance.transactions.edit', $t->id) }}"
                                        class="btn btn-sm btn-primary px-3 rounded-pill"
                                        data-bs-toggle="tooltip" title="تعديل الحركة">
                                        <i class="mdi mdi-pencil-outline me-1"></i> تعديل
                                    </a>
                                    <button wire:click="confirmDelete({{ $t->id }})"
                                        class="btn btn-sm btn-outline-danger px-3 rounded-pill"
                                        data-bs-toggle="tooltip" title="حذف الحركة">
                                        <i class="mdi mdi-delete-outline me-1"></i> حذف
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="mdi mdi-database-remove-outline text-muted" style="font-size: 3rem"></i>
                                    <h5 class="text-muted mt-3">لا توجد معاملات</h5>
                                    <p class="text-muted mb-0">قم بإضافة معاملة جديدة لبدء العمل</p>
                                </div>
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
                    <i class="mdi mdi-database-outline me-1"></i>
                    عرض {{ $transactions->firstItem() }} إلى {{ $transactions->lastItem() }} من
                    {{ $transactions->total() }} معاملة
                </div>
                <div class="d-flex">
                    {{ $transactions->onEachSide(1)->links() }}
                </div>
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
                <p>هل أنت متأكد من رغبتك في حذف هذه الحركة المالية؟ لا يمكن التراجع عن هذا الإجراء.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" wire:click="delete" data-bs-dismiss="modal">
                    <i class="mdi mdi-delete-outline me-1"></i> نعم، احذف
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Delete confirmation modal
        window.addEventListener('showDeleteModal', event => {
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        });
    });
</script>
@endpush

<style>
    .table th {
        border-top: none;
        border-bottom: 2px solid #f3f4f6;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6b7280;
    }
    
    .table td {
        vertical-align: middle;
        padding: 1rem;
    }
    
    .badge {
        padding: 0.35rem 0.75rem;
        font-weight: 500;
        font-size: 0.75rem;
        border-radius: 50px;
    }
    
    .btn {
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
    }
    
    tr:hover {
        background-color: #f9fafb !important;
    }
    
    .modal-header {
        padding: 1.5rem;
    }
    
    .modal-footer {
        padding: 1rem 1.5rem;
    }
</style>