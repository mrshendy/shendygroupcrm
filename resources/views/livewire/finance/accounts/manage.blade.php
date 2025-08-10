<div class="card shadow-sm border-0 overflow-hidden mb-4">
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center m-4 rounded-3 shadow-sm">
            <span class="mdi mdi-check-circle-outline me-3 fs-4"></span>
            <div class="flex-grow-1">{{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card-header bg-light d-flex flex-column flex-md-row justify-content-between align-items-center border-bottom py-3">
        <div class="d-flex align-items-center mb-3 mb-md-0">
            <span class="mdi mdi-bank-outline me-2 fs-3 text-secondary"></span>
            <h5 class="mb-0 fw-semibold">إدارة الحسابات المالية</h5>
        </div>
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white"><span class="mdi mdi-magnify"></span></span>
                <input type="text" wire:model.debounce.500ms="search" class="form-control border-start-0 shadow-none" placeholder="ابحث باسم الحساب، الرقم أو النوع...">
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        <form wire:submit.prevent="save" class="needs-validation" novalidate>
            <div class="row g-3">
                <input type="hidden" wire:model="account_id">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">اسم الحساب <span class="text-danger">*</span></label>
                    <div class="input-group has-validation">
                        <span class="input-group-text bg-light"><span class="mdi mdi-account-outline"></span></span>
                        <input type="text" wire:model.defer="name" class="form-control shadow-sm @error('name') is-invalid @enderror" placeholder="أدخل اسم الحساب" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">رقم الحساب</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><span class="mdi mdi-identifier"></span></span>
                        <input type="text" wire:model.defer="account_number" class="form-control shadow-sm" placeholder="أدخل رقم الحساب">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">نوع الحساب</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text bg-light"><span class="mdi mdi-form-select"></span></span>
                        <select wire:model.defer="type" class="form-select shadow-sm @error('type') is-invalid @enderror">
                            <option value="">اختر النوع...</option>
                            <option value="bank">بنكي</option>
                            <option value="cash">نقدي</option>
                            <option value="wallet">محفظه</option>
                            <option value="investment">استثمار</option>
                            <option value="instapay">انستا باي</option>

                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">الرصيد الافتتاحي</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text bg-light"><span class="mdi mdi-cash"></span></span>
                        <input type="number" wire:model.defer="opening_balance" class="form-control shadow-sm @error('opening_balance') is-invalid @enderror" placeholder="0.00" step="0.01">
                        <span class="input-group-text bg-light">ج.م</span>
                        @error('opening_balance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">اسم البنك</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><span class="mdi mdi-bank"></span></span>
                        <input type="text" wire:model.defer="bank" class="form-control shadow-sm" placeholder="أدخل اسم البنك">
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">ملاحظات</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light align-items-start"><span class="mdi mdi-note-text-outline"></span></span>
                        <textarea wire:model.defer="notes" class="form-control shadow-sm" rows="3" placeholder="أدخل أي ملاحظات إضافية..."></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch ps-0">
                        <input class="form-check-input ms-0" type="checkbox" wire:model.defer="is_main" id="is_main">
                        <label class="form-check-label fw-semibold ms-3" for="is_main">
                            <span class="mdi mdi-star-outline me-2"></span> حساب رئيسي
                        </label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch ps-0">
                        <input class="form-check-input ms-0" type="checkbox" wire:model.defer="status" id="status">
                        <label class="form-check-label fw-semibold ms-3" for="status">
                            <span class="mdi mdi-power-plug-outline me-2"></span> نشط الحساب
                        </label>
                    </div>
                </div>

               <div class="col-12">
    <div class="d-flex justify-content-between align-items-center border-top pt-4">
        <div>
            @if($account_id)
                <button type="button" wire:click="resetInputs" class="btn btn-light border text-danger px-4">
                    <span class="mdi mdi-close-circle-outline me-1"></span>
                    إلغاء التعديل
                </button>
            @endif
        </div>

        <div>
            <button type="submit" class="btn btn-{{ $account_id ? 'success' : 'primary' }} px-4">
                <span class="mdi mdi-content-save{{ $account_id ? '-edit' : '' }}-outline me-1"></span>
                {{ $account_id ? 'تحديث الحساب' : 'حفظ الحساب' }}
            </button>
        </div>
    </div>
</div>

            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 fw-semibold">اسم الحساب</th>
                        <th class="fw-semibold">النوع</th>
                        <th class="fw-semibold">رقم الحساب</th>
                        <th class="fw-semibold text-end">الرصيد</th>
                        <th class="text-center fw-semibold">رئيسي</th>
                        <th class="text-center fw-semibold">الحالة</th>
                        <th class="text-center fw-semibold pe-4">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $acc)
                        <tr wire:key="account-{{ $acc->id }}">
                            <td class="ps-4">{{ $acc->name }}</td>
                            <td>
                                <span class="badge bg-opacity-10 
                                    {{ $acc->type == 'bank' ? 'bg-primary text-primary' : 
                                       ($acc->type == 'cash' ? 'bg-info text-info' : 'bg-warning text-warning')
                                       ($acc->type == 'wallet' ? 'bg-info text-info' : 'bg-warning text-warning')
                                       ($acc->type == 'investment' ? 'bg-info text-info' : 'bg-warning text-warning')
                                       ($acc->type == 'instapay' ? 'bg-info text-info' : 'bg-warning text-warning')   }}">
                                    {{ $acc->type }}
                                </span>
                            </td>
                            <td>{{ $acc->account_number ?? '--' }}</td>
                            <td class="fw-bold text-end {{ $acc->opening_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($acc->opening_balance, 2) }} ج.م
                            </td>
                            <td class="text-center">
                                @if($acc->is_main)
                                    <span class="mdi mdi-star-circle text-warning fs-5" title="حساب رئيسي"></span>
                                @else
                                    <span class="mdi mdi-star-outline text-muted fs-5"></span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-opacity-10 {{ $acc->status ? 'bg-success text-success' : 'bg-danger text-danger' }}">
                                    {{ $acc->status ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <button wire:click="edit({{ $acc->id }})" class="btn btn-sm btn-outline-primary px-2 me-1" title="تعديل">
                                    <span class="mdi mdi-pencil-outline"></span>
                                </button>
                                <button wire:click="delete({{ $acc->id }})" 
                                        onclick="confirm('هل أنت متأكد من حذف هذا الحساب؟') || event.stopImmediatePropagation()"
                                        class="btn btn-sm btn-outline-danger px-2" title="حذف">
                                    <span class="mdi mdi-trash-can-outline"></span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <span class="mdi mdi-database-remove-outline fs-2 d-block mb-2"></span>
                                لا توجد حسابات مسجلة
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($accounts->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="text-muted small mb-2 mb-md-0">
                        <span class="mdi mdi-counter me-1"></span>
                        عرض <span class="fw-semibold">{{ $accounts->firstItem() }}</span> إلى 
                        <span class="fw-semibold">{{ $accounts->lastItem() }}</span> من 
                        <span class="fw-semibold">{{ $accounts->total() }}</span> نتيجة
                    </div>
                    <div>{{ $accounts->onEachSide(1)->links() }}</div>
                </div>
            </div>
        @endif
    </div>
</div>
