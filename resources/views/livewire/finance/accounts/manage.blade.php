@if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 py-2 px-3">
        <span class="mdi mdi-check-circle-outline me-2 fs-4"></span>
        <div>{{ session('message') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-header bg-light text-dark py-3">
        <div class="d-flex align-items-center">
            <span class="mdi mdi-bank-outline me-2 fs-4"></span>
            <h5 class="mb-0">إدارة الحسابات المالية</h5>
        </div>
    </div>
    
    <div class="card-body">
        <form wire:submit.prevent="save" class="needs-validation" novalidate>
            <div class="row g-3">
                <!-- الصف الأول -->
                <div class="col-md-6">
                    <label for="name" class="form-label fw-semibold">
                        <span class="mdi mdi-account-outline me-1"></span>
                        اسم الحساب <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <span class="mdi mdi-form-textbox"></span>
                        </span>
                        <input type="text" wire:model="name" class="form-control shadow-sm" id="name" required placeholder="أدخل اسم الحساب">
                    </div>
                    @error('name') 
                        <div class="invalid-feedback d-block mt-1 small">
                            <span class="mdi mdi-alert-circle-outline me-1"></span>
                            {{ $message }}
                        </div> 
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="account_number" class="form-label fw-semibold">
                        <span class="mdi mdi-numeric me-1"></span>
                        رقم الحساب
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <span class="mdi mdi-identifier"></span>
                        </span>
                        <input type="text" wire:model="account_number" class="form-control shadow-sm" id="account_number" placeholder="أدخل رقم الحساب">
                    </div>
                </div>
                
                <!-- الصف الثاني -->
                <div class="col-md-4">
                    <label for="type" class="form-label fw-semibold">
                        <span class="mdi mdi-form-dropdown me-1"></span>
                        نوع الحساب
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <span class="mdi mdi-format-list-bulleted-type"></span>
                        </span>
                        <select wire:model="type" class="form-select shadow-sm" id="type">
                            <option value="">اختر النوع...</option>
                            <option value="بنكي">بنكي</option>
                            <option value="نقدي">نقدي</option>
                            <option value="إلكتروني">إلكتروني</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <label for="opening_balance" class="form-label fw-semibold">
                        <span class="mdi mdi-cash-multiple me-1"></span>
                        الرصيد الافتتاحي
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <span class="mdi mdi-currency-egp"></span>
                        </span>
                        <input type="number" wire:model="opening_balance" class="form-control shadow-sm" id="opening_balance" step="0.01" placeholder="0.00">
                        <span class="input-group-text">ج.م</span>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <label for="bank" class="form-label fw-semibold">
                        <span class="mdi mdi-bank me-1"></span>
                        اسم البنك
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <span class="mdi mdi-office-building-outline"></span>
                        </span>
                        <input type="text" wire:model="bank" class="form-control shadow-sm" id="bank" placeholder="أدخل اسم البنك">
                    </div>
                </div>
                
                <!-- الصف الثالث -->
                <div class="col-12">
                    <div class="form-check form-switch ps-0">
                        <div class="d-flex align-items-center">
                            <input class="form-check-input ms-0 me-2" type="checkbox" wire:model="is_main" id="is_main" style="width: 2.5em; height: 1.25em;">
                            <label class="form-check-label fw-semibold" for="is_main">
                                <span class="mdi mdi-star-outline me-1"></span>
                                حساب رئيسي
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4 py-2">
                    <span class="mdi mdi-content-save-outline me-2"></span>
                    حفظ الحساب
                </button>
            </div>
        </form>

        <hr class="my-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 d-flex align-items-center">
                <span class="mdi mdi-format-list-bulleted me-2"></span>
                قائمة الحسابات
            </h5>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <span class="mdi mdi-magnify"></span>
                    </span>
                    <input type="text" wire:model="search" class="form-control shadow-sm" placeholder="ابحث باسم الحساب أو الرقم...">
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="30%" class="ps-4">
                            <span class="mdi mdi-account-outline me-1"></span>
                            اسم الحساب
                        </th>
                        <th width="20%">
                            <span class="mdi mdi-form-dropdown me-1"></span>
                            النوع
                        </th>
                        <th width="20%">
                            <span class="mdi mdi-identifier me-1"></span>
                            رقم الحساب
                        </th>
                        <th width="20%">
                            <span class="mdi mdi-cash-multiple me-1"></span>
                            الرصيد
                        </th>
                        <th width="10%" class="text-center">
                            <span class="mdi mdi-star-outline me-1"></span>
                            رئيسي
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $acc)
                        <tr>
                            <td class="ps-4">{{ $acc->name }}</td>
                            <td>
                                <span class="badge bg-{{ $acc->type == 'بنكي' ? 'primary' : ($acc->type == 'نقدي' ? 'info' : 'warning') }} bg-opacity-10 text-{{ $acc->type == 'بنكي' ? 'primary' : ($acc->type == 'نقدي' ? 'info' : 'warning') }}">
                                    {{ $acc->type }}
                                </span>
                            </td>
                            <td>{{ $acc->account_number ?? '--' }}</td>
                            <td class="fw-bold {{ $acc->opening_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($acc->opening_balance, 2) }} ج.م
                            </td>
                            <td class="text-center">
                                @if($acc->is_main)
                                    <span class="mdi mdi-star text-warning fs-5"></span>
                                @else
                                    <span class="mdi mdi-star-outline text-muted fs-5"></span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
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
                    عرض <span class="fw-semibold">{{ $accounts->firstItem() }}</span> إلى <span class="fw-semibold">{{ $accounts->lastItem() }}</span> من <span class="fw-semibold">{{ $accounts->total() }}</span> حساب
                </div>
                <div>
                    {{ $accounts->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    </div>
</div>