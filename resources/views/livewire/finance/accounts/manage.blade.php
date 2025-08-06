@if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 border-0 shadow-sm">
        <span class="mdi mdi-check-circle-outline me-2 fs-4 text-success"></span>
        <div class="flex-grow-1">{{ session('message') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm border-0 overflow-hidden">
    <!-- Card Header -->
    <div class="card-header bg-light d-flex justify-content-between align-items-center border-bottom py-3">
        <div class="d-flex align-items-center">
            <span class="mdi mdi-bank-outline me-2 fs-4 text-primary"></span>
            <h5 class="mb-0 fw-semibold">إدارة الحسابات المالية</h5>
        </div>
        <div class="col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0">
                    <span class="mdi mdi-magnify text-muted"></span>
                </span>
                <input type="text" wire:model.debounce.500ms="search" class="form-control shadow-none border-start-0"
                    placeholder="ابحث عن حساب...">
            </div>
        </div>
    </div>

    <!-- Card Body -->
    <div class="card-body p-4">
        <!-- Form Section -->
        <div class="mb-5">
            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <input type="hidden" wire:model="account_id">

                    <!-- Account Name -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small mb-1">اسم الحساب <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><span class="mdi mdi-account-outline text-muted"></span></span>
                            <input type="text" wire:model.defer="name" class="form-control shadow-sm" placeholder="أدخل اسم الحساب">
                        </div>
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- Account Number -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small mb-1">رقم الحساب</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><span class="mdi mdi-identifier text-muted"></span></span>
                            <input type="text" wire:model.defer="account_number" class="form-control shadow-sm" placeholder="أدخل رقم الحساب">
                        </div>
                    </div>

                    <!-- Account Type -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small mb-1">نوع الحساب</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><span class="mdi mdi-format-list-bulleted-type text-muted"></span></span>
                            <select wire:model.defer="type" class="form-select shadow-sm">
                                <option value="">اختر النوع...</option>
                                <option value="بنكي">بنكي</option>
                                <option value="نقدي">نقدي</option>
                                <option value="إلكتروني">إلكتروني</option>
                            </select>
                        </div>
                        @error('type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- Opening Balance -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small mb-1">الرصيد الافتتاحي</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><span class="mdi mdi-currency-egp text-muted"></span></span>
                            <input type="number" wire:model.defer="opening_balance" class="form-control shadow-sm" placeholder="0.00" step="0.01">
                            <span class="input-group-text bg-light">ج.م</span>
                        </div>
                    </div>

                    <!-- Bank Name -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small mb-1">اسم البنك</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><span class="mdi mdi-bank text-muted"></span></span>
                            <input type="text" wire:model.defer="bank" class="form-control shadow-sm" placeholder="أدخل اسم البنك">
                        </div>
                    </div>

                    <!-- Is Main Account -->
                    <div class="col-12">
                        <div class="form-check form-switch ps-0">
                            <input class="form-check-input ms-0" type="checkbox" wire:model.defer="is_main" id="is_main">
                            <label class="form-check-label fw-semibold ms-2" for="is_main">
                                <span class="mdi mdi-star-outline me-1"></span> حساب رئيسي
                            </label>
                        </div>
                    </div>

                    <!-- Form Actions -->
<div class="col-12">
    <div class="d-flex justify-content-end border-top pt-3">
        @if ($account_id)
            <button type="button" wire:click="resetInputs" class="btn btn-outline-secondary me-2">
                <span class="mdi mdi-close-circle-outline me-1"></span> إلغاء
            </button>
            <button type="submit" class="btn btn-success px-4 py-2">
                <span class="mdi mdi-content-save-edit-outline me-2"></span> تحديث الحساب
            </button>
        @else
            <button type="submit" class="btn btn-primary px-4 py-2">
                <span class="mdi mdi-content-save-outline me-2"></span> حفظ الحساب
            </button>
        @endif
    </div>
</div>


        <!-- Accounts Table -->
        <div class="mt-4">
            <div class="table-responsive rounded-3 border">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 fw-semibold">اسم الحساب</th>
                            <th class="fw-semibold">النوع</th>
                            <th class="fw-semibold">رقم الحساب</th>
                            <th class="fw-semibold">الرصيد</th>
                            <th class="text-center fw-semibold">رئيسي</th>
                            <th class="text-center fw-semibold pe-4">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $acc)
                            <tr>
                                <td class="ps-4">{{ $acc->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $acc->type == 'بنكي' ? 'primary' : ($acc->type == 'نقدي' ? 'info' : 'warning') }}">
                                        {{ $acc->type }}
                                    </span>
                                </td>
                                <td>{{ $acc->account_number ?? '--' }}</td>
                                <td class="fw-bold {{ $acc->opening_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($acc->opening_balance, 2) }} ج.م
                                </td>
                                <td class="text-center">
                                    @if ($acc->is_main)
                                        <span class="mdi mdi-star text-warning fs-5"></span>
                                    @else
                                        <span class="mdi mdi-star-outline text-muted fs-5"></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button wire:click="edit({{ $acc->id }})" class="btn btn-sm btn-outline-primary">
                                        تعديل
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <span class="mdi mdi-database-remove-outline fs-2 d-block mb-2"></span>
                                    لا توجد حسابات مسجلة
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($accounts->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        عرض <span class="fw-semibold">{{ $accounts->firstItem() }}</span> إلى
                        <span class="fw-semibold">{{ $accounts->lastItem() }}</span> من
                        <span class="fw-semibold">{{ $accounts->total() }}</span> نتيجة
                    </div>
                    <div>
                        {{ $accounts->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
