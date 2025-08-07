<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">قائمة المصروفات و التحصيلات</h4>
            <div>
                <a href="{{ route('finance.transactions.create.expense') }}" class="btn btn-danger">
                    <i class="mdi mdi-minus-circle-outline me-1"></i> إضافة مصروف
                </a>

                <a href="{{ route('finance.transactions.create.income') }}" class="btn btn-success">
                    <i class="mdi mdi-plus-circle-outline me-1"></i> إضافة تحصيل
                </a>

            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>رقم الحركة</th>
                        <th>المبلغ</th>
                        <th>المستلم</th>
                        <th>النوع</th>
                        <th>البند</th>
                        <th>ملاحظات</th>
                        <th>تاريخ الحركة</th>
                        <th>تاريخ الإنشاء</th>
                        <th>إدارة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $t)
                        <tr>
                            <td>{{ $transactions->firstItem() + $index }}</td>
                            <td>{{ $t->id }}</td>
                            <td>{{ number_format($t->amount, 2) }}</td>
                            <td>{{ $t->account->name ?? '-' }}</td>
                            <td>
                                <span class="{{ $t->transaction_type === 'مصروف' ? 'text-danger' : 'text-success' }}">
                                    {{ $t->transaction_type }}
                                </span>
                            </td>
                            <td>{{ $t->item->name ?? '-' }}</td>
                            <td>{{ $t->notes ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($t->transaction_date)->format('Y-m-d') }}</td>
                            <td>{{ \Carbon\Carbon::parse($t->created_at)->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        إدارة
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item text-danger"
                                                wire:click.prevent="delete({{ $t->id }})">
                                                <i class="mdi mdi-delete-outline"></i> إلغاء الحركة
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                <i class="mdi mdi-database-remove-outline d-block fs-3 mb-2"></i>
                                لا توجد حركات مسجلة حالياً
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transactions->hasPages())
            <div class="mt-3">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Modal for Create Transaction --}}
@if ($showModal)
    <div class="modal fade show d-block" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $transaction_type === 'تحصيل' ? 'إضافة تحصيل جديد' : 'إضافة مصروف جديد' }}
                    </h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <div class="modal-body">
                    @include('livewire.finance.transactions._form')
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
@endif
