<div class="container mt-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <input type="text" class="form-control" placeholder="بحث باسم الحساب أو النوع" wire:model.debounce.500ms="search">
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="bg-light">
                    <tr>
                        <th>اسم الحساب</th>
                        <th>النوع</th>
                        <th>الرصيد الافتتاحي</th>
                        <th>الرصيد الحالي</th>
                        <th>الحالة</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @forelse ($accounts as $account)
                        <tr>
                            <td>{{ $account->name }}</td>
                            <td>{{ ucfirst($account->type) }}</td>
                            <td>{{ number_format($account->opening_balance, 2) }}</td>
                            <td>{{ number_format($account->current_balance, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $account->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ $account->status == 'active' ? 'نشط' : 'موقوف' }}
                                </span>
                            </td>
                            <td>{{ $account->notes }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">لا توجد حسابات مسجلة.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

       <!-- Pagination -->
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    عرض <span class="fw-semibold">{{ $accounts->firstItem() }}</span> إلى <span class="fw-semibold">{{ $accounts->lastItem() }}</span> من <span class="fw-semibold">{{ $accounts->total() }}</span> حساب
                </div>
                <div>
                    {{ $accounts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
