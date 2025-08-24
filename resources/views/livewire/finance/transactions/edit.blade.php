<div class="card shadow-sm border-0 overflow-hidden" dir="rtl">
    <div class="card-header bg-light d-flex justify-content-between align-items-center border-bottom">
        <h5 class="mb-0">تعديل حركة مالية</h5>
        <a href="{{ route('finance.transactions.index') }}" class="btn btn-sm btn-outline-secondary">رجوع</a>
    </div>

    <div class="card-body p-4">
        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show mb-3">
                <i class="mdi mdi-check-circle-outline me-2"></i> {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                <i class="mdi mdi-alert-circle-outline me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form wire:submit.prevent="save">
            <div class="row g-3">
                <!-- نوع الحركة -->
                <div class="col-12">
                    <span class="badge {{ $type === 'مصروفات' ? 'bg-danger' : 'bg-success' }} px-3 py-2 fs-6 shadow-sm">
                        {{ $type }}
                    </span>
                </div>

                <!-- من -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">من <span class="text-danger">*</span></label>
                    <select wire:model.defer="from_account_id" class="form-select">
                        <option value="">— اختر الحساب —</option>
                        @foreach($accounts as $a)
                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                        @endforeach
                    </select>
                    @error('from_account_id')
                        <small class="text-danger d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</small>
                    @enderror
                </div>

                <!-- إلى -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">إلى <span class="text-danger">*</span></label>
                    <select wire:model.defer="to_account_id" class="form-select">
                        <option value="">— اختر الحساب —</option>
                        @foreach($accounts as $a)
                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                        @endforeach
                    </select>
                    @error('to_account_id')
                        <small class="text-danger d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</small>
                    @enderror
                </div>

                <!-- البند -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">البند <span class="text-danger">*</span></label>
                    <select wire:model.defer="item_id" class="form-select">
                        <option value="">— اختر البند —</option>
                        @foreach($items as $it)
                            <option value="{{ $it->id }}">{{ $it->name }}</option>
                        @endforeach
                    </select>
                    @error('item_id')
                        <small class="text-danger d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</small>
                    @enderror
                </div>

                <!-- المبلغ -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">المبلغ <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" wire:model.defer="amount" class="form-control" placeholder="0.00">
                    @error('amount')
                        <small class="text-danger d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</small>
                    @enderror
                </div>

                <!-- التاريخ -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">تاريخ الحركة <span class="text-danger">*</span></label>
                    <input type="date" wire:model.defer="transaction_date" class="form-control">
                    @error('transaction_date')
                        <small class="text-danger d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</small>
                    @enderror
                </div>

                <!-- ✅ حقول إضافية خاصة بالإيراد -->
                @if($type === 'إيراد' || $type === 'تحصيل')
                    <div class="col-md-6">
                        <label class="form-label fw-bold">نوع الإيراد</label>
                        <input type="text" wire:model.defer="collection_type" class="form-control" placeholder="مثال: تحصيل من عميل">
                        @error('collection_type')
                            <small class="text-danger d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">العميل</label>
                        <select wire:model.defer="client_id" class="form-select">
                            <option value="">— اختر العميل —</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <small class="text-danger d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</small>
                        @enderror
                    </div>
                @endif

                <!-- الملاحظات -->
                <div class="col-12">
                    <label class="form-label fw-bold">ملاحظات</label>
                    <textarea wire:model.defer="notes" class="form-control" rows="3" placeholder="أدخل أي تفاصيل إضافية..."></textarea>
                    @error('notes')
                        <small class="text-danger d-block"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</small>
                    @enderror
                </div>

                <!-- زر الحفظ -->
                <div class="col-12 text-end mt-4">
                    <button class="btn btn-primary px-4 rounded-pill" type="submit">
                        <i class="mdi mdi-content-save-outline me-1"></i> حفظ التعديلات
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
