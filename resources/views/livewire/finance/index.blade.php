<div class="container mt-4">
    <!-- Search Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <span class="mdi mdi-magnify"></span>
                </span>
                <input type="text" class="form-control border-start-0 shadow-none" 
                       placeholder="ابحث باسم الحساب، النوع أو الملاحظات..."
                       wire:model.debounce.500ms="search">
            </div>
        </div>
    </div>

    <!-- Accounts Table Card -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 fw-semibold">اسم الحساب</th>
                        <th class="fw-semibold">النوع</th>
                        <th class="fw-semibold text-end">الرصيد الافتتاحي</th>
                        <th class="fw-semibold text-end">الرصيد الحالي</th>
                        <th class="fw-semibold text-center">الحالة</th>
                        <th class="fw-semibold">ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($accounts as $account)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <span class="mdi mdi-account-circle-outline me-2 text-success"></span>
                                    {{ $account->name }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-opacity-10 
                                    {{ $account->type == 'بنكي' ? 'bg-primary text-primary' : 
                                       ($account->type == 'نقدي' ? 'bg-info text-info' : 'bg-warning text-warning') }}">
                                    {{ ucfirst($account->type) }}
                                </span>
                            </td>
                            <td class="text-end fw-semibold">
                                {{ number_format($account->opening_balance, 2) }} ج.م
                            </td>
                            <td class="text-end fw-semibold 
                                {{ $account->current_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($account->current_balance, 2) }} ج.م
                            </td>
                            <td class="text-center">
                                @if ($account->status)
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        <span class="mdi mdi-check-circle-outline me-1"></span> نشط
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        <span class="mdi mdi-close-circle-outline me-1"></span> غير نشط
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($account->notes)
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                          title="{{ $account->notes }}">
                                        <span class="mdi mdi-note-text-outline me-1 text-muted"></span>
                                        {{ $account->notes }}
                                    </span>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <span class="mdi mdi-database-remove-outline fs-2 d-block mb-2"></span>
                                لا توجد حسابات مسجلة
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    <span class="mdi mdi-counter me-1"></span>
                    عرض <span class="fw-semibold">{{ $accounts->firstItem() }}</span> إلى 
                    <span class="fw-semibold">{{ $accounts->lastItem() }}</span> من 
                    <span class="fw-semibold">{{ $accounts->total() }}</span> نتيجة
                </div>
                <div>
                    {{ $accounts->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    </div>
</div>