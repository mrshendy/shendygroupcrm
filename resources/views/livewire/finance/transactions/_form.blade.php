<form wire:submit.prevent="save">
    <div class="mb-3">
        <label class="form-label">المستلم</label>
        <select class="form-select" wire:model.defer="account_id">
            <option value="">اختر الحساب</option>
            @foreach($accounts as $acc)
                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">البند</label>
        <select class="form-select" wire:model.defer="item_id">
            <option value="">اختر البند</option>
            @foreach($items as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">المبلغ</label>
        <input type="number" step="0.01" class="form-control" wire:model.defer="amount">
    </div>

    <div class="mb-3">
        <label class="form-label">تاريخ الحركة</label>
        <input type="date" class="form-control" wire:model.defer="transaction_date">
    </div>

    <div class="mb-3">
        <label class="form-label">ملاحظات</label>
        <textarea class="form-control" wire:model.defer="notes"></textarea>
    </div>

    <div class="mt-4 d-flex justify-content-end">
        <button type="button" class="btn btn-outline-secondary me-2" wire:click="$set('showModal', false)">
            إلغاء
        </button>
        <button type="submit" class="btn btn-primary">
            حفظ
        </button>
    </div>
</form>
