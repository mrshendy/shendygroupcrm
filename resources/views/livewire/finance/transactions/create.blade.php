
<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0">{{ $transaction_type === 'تحصيل' ? 'إضافة تحصيل' : 'إضافة مصروف' }}</h5>
    </div>

    <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <form wire:submit.prevent="save">
            <div class="mb-3">
                <label class="form-label">المستلم</label>
                <select wire:model="account_id" class="form-select">
                    <option value="">اختر الحساب</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                    @endforeach
                </select>
                @error('account_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">البند</label>
                <select wire:model="item_id" class="form-select">
                    <option value="">اختر البند</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('item_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">المبلغ</label>
                <input type="number" step="0.01" wire:model="amount" class="form-control">
                @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">تاريخ الحركة</label>
                <input type="date" wire:model="transaction_date" class="form-control">
                @error('transaction_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">ملاحظات</label>
                <textarea wire:model="notes" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="mdi mdi-content-save-outline me-1"></i> حفظ
            </button>
        </form>
    </div>
</div>
