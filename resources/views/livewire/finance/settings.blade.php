<div>
    <!-- كارد إعدادات الإدارة المالية -->
    <div class="card shadow-sm mb-3 cursor-pointer" wire:click="toggleFinanceSettings">
        <div class="card-body d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="mdi mdi-bank-outline me-2"></i>إعدادات الإدارة المالية</h5>
            <i class="mdi mdi-chevron-down"></i>
        </div>
    </div>

    @if ($showFinanceSettings)
        <!-- كاردات البنود والحسابات -->
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-0"><i class="mdi mdi-bookmark-outline me-2"></i>البنود</h6>
                        <a href="{{ route('finance.items.index') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-0"><i class="mdi mdi-wallet-outline me-2"></i>الحسابات</h6>
                        <a href="{{ route('finance.accounts.index') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
