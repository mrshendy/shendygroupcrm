<div class="card shadow-sm border-0 overflow-hidden" dir="rtl">
    <div class="card-header bg-light d-flex justify-content-between align-items-center border-bottom">
        <h5 class="mb-0"><i class="mdi mdi-cash-minus me-2 text-danger"></i>إضافة مصروف</h5>
        <a href="{{ route('finance.transactions.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="mdi mdi-arrow-left-circle-outline me-1"></i> رجوع
        </a>
    </div>

    <div class="card-body p-4">
        @if (session()->has('message'))
            <div class="alert alert-success d-flex align-items-center">
                <i class="mdi mdi-check-circle-outline me-2"></i>
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="save">
            <div class="row g-3">

                <!-- من -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">من <span class="text-danger">*</span></label>
                    <input type="text" class="form-control mb-2" placeholder="ابحث عن الحساب..."
                           wire:model.debounce.500ms="searchFromAccount">
                    <select wire:model.defer="from_account_id" class="form-select">
                        <option value="">— اختر الحساب —</option>
                        @foreach ($fromAccounts as $a)
                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                        @endforeach
                    </select>
                    @error('from_account_id')
                        <small class="text-danger d-block mt-1">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </small>
                    @enderror
                </div>

                <!-- إلى -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">إلى <span class="text-danger">*</span></label>
                    <input type="text" class="form-control mb-2" placeholder="ابحث عن الحساب..."
                           wire:model.debounce.500ms="searchToAccount">
                    <select wire:model.defer="to_account_id" class="form-select">
                        <option value="">— اختر الحساب —</option>
                        @foreach ($toAccounts as $a)
                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                        @endforeach
                    </select>
                    @error('to_account_id')
                        <small class="text-danger d-block mt-1">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </small>
                    @enderror
                </div>

                <!-- البند -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">البند <span class="text-danger">*</span></label>
                    <select wire:model.defer="item_id" class="form-select">
                        <option value="">— اختر البند —</option>
                        @foreach ($items as $it)
                            <option value="{{ $it->id }}">{{ $it->name }}</option>
                        @endforeach
                    </select>
                    @error('item_id')
                        <small class="text-danger d-block mt-1">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </small>
                    @enderror
                </div>

                <!-- المبلغ -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">المبلغ <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" wire:model.defer="amount" class="form-control" placeholder="0.00">
                    @error('amount')
                        <small class="text-danger d-block mt-1">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </small>
                    @enderror
                </div>

                <!-- التاريخ -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">تاريخ الحركة <span class="text-danger">*</span></label>
                    <input type="date" wire:model.defer="transaction_date" class="form-control">
                    @error('transaction_date')
                        <small class="text-danger d-block mt-1">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </small>
                    @enderror
                </div>

                <!-- الملاحظات -->
                <div class="col-12">
                    <label class="form-label fw-bold">ملاحظات</label>
                    <textarea wire:model.defer="notes" class="form-control" rows="3" placeholder="أدخل أي تفاصيل إضافية..."></textarea>
                    @error('notes')
                        <small class="text-danger d-block mt-1">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </small>
                    @enderror
                </div>

                <!-- زر الحفظ -->
                <div class="col-12 text-end mt-3">
                    <button class="btn btn-danger px-4 rounded-pill" type="submit">
                        <i class="mdi mdi-content-save-outline me-1"></i> حفظ
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
