<div>
    <!-- Finance Settings Card -->
    <div class="col-xxl-4 col-lg-6">
        <div class="card card-body text-center h-100">
            <div class="avatar-md mx-auto mb-3">
                <div class="avatar-title bg-soft-light border border-info p-2 text-success rounded">
                    <script src="{{URL::asset('assets/images/icon/bhenfmcm.js')}}"></script>
                    <lord-icon
                        src="{{URL::asset('assets/images/icon/hpnrikyx.json')}}"
                        trigger="hover"
                        colors="primary:#4bb3fd,secondary:#ebe6ef"
                        style="width:250px;height:250px">
                    </lord-icon>
                    

                </div>
            </div>
            <h4 class="card-title font">إعدادات الإدارة المالية</h4>
            
            <!-- Toggle Button -->
            <button class="btn btn-info mb-3" wire:click="toggleFinanceSettings">
                اذهب الان الي الاعدادات
                <i class="mdi mdi-chevron-{{ $showFinanceSettings ? 'up' : 'down' }} ms-1"></i>
            </button>

            <!-- Finance Options -->
            @if ($showFinanceSettings)
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center p-3">
                                <div class="avatar-sm mx-auto mb-3">
                                    <div class="avatar-title bg-soft-primary text-primary rounded">
                                        <i class="mdi mdi-bookmark-outline fs-4"></i>
                                    </div>
                                </div>
                                <h5 class="fs-16 mb-1"> البنود</h5>
                                <a href="{{ route('finance.items.index') }}" class="btn btn-sm btn-outline-primary stretched-link mt-2">
                                    اذهب الان الي البنود
                                    <i class="mdi mdi-arrow-left ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center p-3">
                                <div class="avatar-sm mx-auto mb-3">
                                    <div class="avatar-title bg-soft-success text-success rounded">
                                        <i class="mdi mdi-wallet-outline fs-4"></i>
                                    </div>
                                </div>
                                <h5 class="fs-16 mb-1">الحسابات</h5>
                                <a href="{{ route('finance.accounts.index') }}" class="btn btn-sm btn-outline-success stretched-link mt-2">
                                    اذهب الان الي الحسابات
                                    <i class="mdi mdi-arrow-left ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>


<script src="https://cdn.lordicon.com/lordicon.js"></script>

