<div class="card shadow-sm border-0 overflow-hidden">
    <div class="card-body p-4">
        <!-- Card Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div class="d-flex align-items-center mb-3 mb-md-0">
                <lord-icon
                    src="{{URL::asset('assets/images/icon/finance-transactions.json')}}"
                    trigger="loop"
                    colors="primary:#4b38b3,secondary:#08a88a"
                    style="width:50px;height:50px">
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
                        <th width="50px">#</th>
                        <th width="120px">رقم الحركة</th>
                        <th width="120px" class="text-end">المبلغ</th>
                        <th>المستلم</th>
                        <th width="120px">النوع</th>
                        <th>البند</th>
                        <th width="200px">ملاحظات</th>
                        <th width="120px">تاريخ الحركة</th>
                        <th width="150px">تاريخ الإنشاء</th>
                        <th width="100px" class="text-center">إدارة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $t)
                    <tr wire:key="transaction-{{ $t->id }}">
                        <td>{{ $transactions->firstItem() + $index }}</td>
                        <td class="fw-semibold">#{{ $t->id }}</td>
                        <td class="text-end fw-bold {{ $t->transaction_type === 'مصروف' ? 'text-danger' : 'text-success' }}">
                            {{ number_format($t->amount, 2) }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="mdi mdi-account-circle-outline me-2 text-muted"></span>
                                {{ $t->account->name ?? '-' }}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $t->transaction_type === 'مصروف' ? 'danger' : 'success' }}-subtle text-{{ $t->transaction_type === 'مصروف' ? 'danger' : 'success' }}">
                                {{ $t->item->type ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="mdi mdi-tag-outline me-2 text-muted"></span>
                                {{ $t->item->name ?? '-' }}
                            </div>
                        </td>
                        <td>
                            @if($t->notes)
                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $t->notes }}">
                                <span class="mdi mdi-note-text-outline me-1 text-muted"></span>
                                {{ $t->notes }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($t->transaction_date)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($t->created_at)->format('Y-m-d H:i') }}</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-cog"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item text-danger" wire:click.prevent="delete({{ $t->id }})">
                                            <i class="mdi mdi-delete-outline me-1"></i> إلغاء الحركة
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <lord-icon
                                src="{{URL::asset('assets/images/icon/empty-data.json')}}"
                                trigger="loop"
                                colors="primary:#ccc,secondary:#eee"
                                style="width:100px;height:100px">
                            </lord-icon>
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

<!-- Modal -->
@if ($showModal)
<div class="modal fade show d-block" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title d-flex align-items-center">
                    <lord-icon
                        src="{{ $transaction_type === 'تحصيل' ? URL::asset('assets/images/icon/income.json') : URL::asset('assets/images/icon/expense.json') }}"
                        trigger="loop"
                        colors="primary:#4b38b3"
                        style="width:30px;height:30px"
                        class="me-2">
                    </lord-icon>
                    {{ $transaction_type === 'تحصيل' ? 'إضافة تحصيل جديد' : 'إضافة مصروف جديد' }}
                </h5>
                <button type="button" class="btn-close" wire:click="$set('showModal', false)" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                @include('livewire.finance.transactions._form')
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" wire:click="$set('showModal', false)">
                    <i class="mdi mdi-close-circle-outline me-1"></i> إلغاء
                </button>
                <button type="button" class="btn btn-primary" wire:click="save">
                    <i class="mdi mdi-content-save-outline me-1"></i> حفظ
                </button>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
</div>
@endif