@forelse($transactions as $index => $t)
    @php
        $isExpense = ($t->transaction_type === 'مصروفات'); // عدّل القيمة حسب اللي عندك في DB
        $badgeTone = $isExpense ? 'danger' : 'success';
    @endphp

    <tr wire:key="transaction-{{ $t->id }}">
        <td class="ps-3">{{ $transactions->firstItem() + $index }}</td>
        <td class="px-3 fw-semibold">#{{ $t->id }}</td>

        <td class="px-3 text-end fw-bold {{ $isExpense ? 'text-danger' : 'text-success' }}">
            {{ number_format($t->amount, 2) }}
        </td>

        <td class="px-3">
            <div class="d-flex align-items-center">
                <span class="mdi mdi-account-circle-outline me-2 text-muted"></span>
                {{ optional($t->account)->name ?? '-' }}
            </div>
        </td>

        <td class="px-3">
            <span class="badge bg-{{ $badgeTone }}-subtle text-{{ $badgeTone }}">
                {{ $t->transaction_type }}
            </span>
        </td>

        <td class="px-3">
            <div class="d-flex align-items-center">
                <span class="mdi mdi-tag-outline me-2 text-muted"></span>
                {{ optional($t->item)->name ?? '-' }}
            </div>
        </td>

        <td class="px-3">
            @if(!$isExpense)
                <span class="badge bg-info-subtle text-info">{{ $t->collection_type ?? '-' }}</span>
                @if($t->collection_type === 'تحصل من عميل')
                    <div class="small text-muted">
                        {{ optional($t->client)->name ?? '-' }}
                    </div>
                @endif
            @else
                <span class="text-muted">-</span>
            @endif
        </td>

        <td class="px-3">
            @if($t->notes)
                <span class="text-truncate d-inline-block" style="max-width:200px;" title="{{ $t->notes }}">
                    <span class="mdi mdi-note-text-outline me-1 text-muted"></span>{{ $t->notes }}
                </span>
            @else
                <span class="text-muted">-</span>
            @endif
        </td>

        <td class="px-3">{{ \Carbon\Carbon::parse($t->transaction_date)->format('Y-m-d') }}</td>
        <td class="px-3">{{ \Carbon\Carbon::parse($t->created_at)->format('Y-m-d H:i') }}</td>

        <td class="px-3 text-center">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="mdi mdi-cog"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item text-danger" wire:click.prevent="delete({{ $t->id }})">
                            <i class="mdi mdi-delete-outline me-1"></i> إلغاء الحركة
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="11" class="text-center py-5 text-muted">
            <lord-icon src="{{ URL::asset('assets/images/icon/empty-data.json') }}" trigger="loop"
                       colors="primary:#ccc,secondary:#eee" style="width:100px;height:100px"></lord-icon>
            <p class="mt-3">لا توجد حركات مسجلة حالياً</p>
        </td>
    </tr>
@endforelse
