<div class="card shadow-sm border-0 overflow-hidden" dir="rtl">
    <div class="card-header bg-light d-flex justify-content-between align-items-center border-bottom">
        <h5 class="mb-0">إضافة تحصيل</h5>
        <a href="{{ route('finance.transactions.index') }}" class="btn btn-sm btn-outline-secondary">رجوع</a>
    </div>

    <div class="card-body p-4">
        @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <form wire:submit.prevent="save">
            <div class="row g-3">

                <!-- من -->
                <div class="col-md-6">
                    <label class="form-label">من *</label>
                    <input type="text" class="form-control mb-2" placeholder="ابحث عن الحساب..." wire:model.debounce.500ms="searchFromAccount">
                    <select wire:model.defer="from_account_id" class="form-select" required>
                        <option value="">— اختر —</option>
                        @foreach ($fromAccounts as $a)
                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                        @endforeach
                    </select>
                    @error('from_account_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- إلى -->
                <div class="col-md-6">
                    <label class="form-label">إلى *</label>
                    <input type="text" class="form-control mb-2" placeholder="ابحث عن الحساب..." wire:model.debounce.500ms="searchToAccount">
                    <select wire:model.defer="to_account_id" class="form-select" required>
                        <option value="">— اختر —</option>
                        @foreach ($toAccounts as $a)
                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                        @endforeach
                    </select>
                    @error('to_account_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- البند -->
                <div class="col-md-6">
                    <label class="form-label">البند *</label>
                    <select wire:model.defer="item_id" class="form-select" required>
                        <option value="">— اختر —</option>
                        @foreach ($items as $it)
                            <option value="{{ $it->id }}">{{ $it->name }}</option>
                        @endforeach
                    </select>
                    @error('item_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- المبلغ -->
                <div class="col-md-6">
                    <label class="form-label">المبلغ *</label>
                    <input type="number" step="0.01" wire:model.defer="amount" class="form-control" required>
                    @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- التاريخ -->
                <div class="col-md-6">
                    <label class="form-label">تاريخ الحركة *</label>
                    <input type="date" wire:model.defer="transaction_date" class="form-control" required>
                    @error('transaction_date') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- نوع التحصيل -->
                <div class="col-md-6">
                    <label class="form-label">نوع التحصيل</label>
                    <select wire:model="collection_type" class="form-select">
                        <option value="">— اختر —</option>
                        <option value="تحصل من عميل">تحصل من عميل</option>
                        <option value="أخرى">أخرى</option>
                    </select>
                    @error('collection_type') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- العميل -->
                @if ($collection_type === 'تحصل من عميل')
                    <div class="col-md-6">
                        <label class="form-label">العميل</label>
                        <select wire:model.defer="client_id" class="form-select">
                            <option value="">— اختر —</option>
                            @foreach ($clients as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                @endif

                <!-- الملاحظات -->
                <div class="col-12">
                    <label class="form-label">ملاحظات</label>
                    <textarea wire:model.defer="notes" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-success" type="submit">حفظ</button>
                </div>
            </div>
        </form>
    </div>
</div>
