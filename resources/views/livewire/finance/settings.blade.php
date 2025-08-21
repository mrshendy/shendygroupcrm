{{-- resources/views/livewire/finance/settings.blade.php --}}

<div class="container py-4">
    <h3 class="mb-4">إعدادات الإدارة المالية</h3>

    <!-- زر تبديل إظهار/إخفاء -->
    <button class="btn btn-primary mb-3" wire:click="toggleFinanceSettings">
        {{ $showFinanceSettings ? 'إخفاء الإعدادات' : 'عرض الإعدادات' }}
    </button>

    <!-- الكروت -->
    @if ($showFinanceSettings)
        <div class="row">
            <!-- كارت الحسابات -->
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm rounded-3 h-100">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <h5 class="card-title mb-3">الحسابات</h5>
                        <a href="{{ route('finance.accounts.manage') }}" class="btn btn-outline-primary">
                            إدارة الحسابات
                        </a>
                    </div>
                </div>
            </div>

            <!-- كارت البنود -->
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm rounded-3 h-100">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <h5 class="card-title mb-3">البنود</h5>
                        <a href="{{ route('finance.items.index') }}" class="btn btn-outline-primary">
                            إدارة البنود
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
