<div class="card shadow-sm border-0 overflow-hidden">
    <div class="card-header bg-light d-flex justify-content-between align-items-center border-bottom">
        <h5 class="mb-0 d-flex align-items-center">
            <span class="mdi mdi-plus-circle-outline me-2 text-success"></span>
            إضافة تحصيل
        </h5>
        <button wire:click="$emit('closeModal')" class="btn btn-sm btn-outline-secondary">
            <i class="mdi mdi-close"></i>
        </button>
    </div>

    <div class="card-body p-4">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4">
                <span class="mdi mdi-check-circle-outline me-2 fs-4"></span>
                <div>{{ session('message') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form wire:submit.prevent="save" class="needs-validation" novalidate>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">المستلم <span class="text-danger">*</span></label>
                    <div class="input-group has-validation">
                        <span class="input-group-text bg-light"><span class="mdi mdi-account-outline"></span></span>
                        <select wire:model="account_id" class="form-select shadow-sm @error('account_id') is-invalid @enderror" required>
                            <option value="">اختر الحساب...</option>
                            @foreach ($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                            @endforeach
                        </select>
                        @error('account_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">البند <span class="text-danger">*</span></label>
                    <div class="input-group has-validation">
                        <span class="input-group-text bg-light"><span class="mdi mdi-tag-outline"></span></span>
                        <select wire:model="item_id" class="form-select shadow-sm @error('item_id') is-invalid @enderror" required>
                            <option value="">اختر البند...</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('item_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">المبلغ <span class="text-danger">*</span></label>
                    <div class="input-group has-validation">
                        <span class="input-group-text bg-light"><span class="mdi mdi-cash"></span></span>
                        <input type="number" step="0.01" wire:model="amount" class="form-control shadow-sm @error('amount') is-invalid @enderror" placeholder="0.00" required>
                        <span class="input-group-text bg-light">ج.م</span>
                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">تاريخ الحركة <span class="text-danger">*</span></label>
                    <div class="input-group has-validation">
                        <span class="input-group-text bg-light"><span class="mdi mdi-calendar"></span></span>
                        <input type="date" wire:model="transaction_date" class="form-control shadow-sm @error('transaction_date') is-invalid @enderror" required>
                        @error('transaction_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">نوع التحصيل</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><span class="mdi mdi-shape-outline"></span></span>
                        <select wire:model="collection_type" class="form-select">
                            <option value="">اختر النوع...</option>
                            <option value="تحصل من عميل">تحصل من عميل</option>
                            <option value="أخرى">أخرى</option>
                        </select>
                    </div>
                    @error('collection_type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                @if($collection_type === 'تحصل من عميل')
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">اختر العميل</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><span class="mdi mdi-account-multiple-outline"></span></span>
                            <select wire:model="client_id" class="form-select @error('client_id') is-invalid @enderror">
                                <option value="">اختر العميل...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                            @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                @endif

                <div class="col-12">
                    <label class="form-label fw-semibold">ملاحظات</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light align-items-start"><span class="mdi mdi-note-text-outline"></span></span>
                        <textarea wire:model="notes" class="form-control shadow-sm" rows="3" placeholder="أدخل أي ملاحظات إضافية..."></textarea>
                    </div>
                </div>

                <div class="col-12">
                    <div class="d-flex justify-content-end border-top pt-4">
                        <button type="button" wire:click="$emit('closeModal')" class="btn btn-outline-secondary me-3 px-4">
                            <i class="mdi mdi-close-circle-outline me-1"></i> إلغاء
                        </button>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="mdi mdi-content-save-outline me-1"></i> حفظ
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
