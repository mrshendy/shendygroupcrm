<div class="container-fluid px-4 py-3">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2 bg-light p-2 rounded-3">
                    <li class="breadcrumb-item">
                        <a href="#" class="text-decoration-none d-flex align-items-center">
                            <i class="mdi mdi-home me-1"></i> الرئيسية
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('clients.index') }}" class="text-decoration-none">العملاء</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $client->name }}</li>
                </ol>
            </nav>
            
            <div class="d-flex align-items-center">
                <lord-icon
                    src="{{URL::asset('assets/images/icon/client-profile.json')}}"
                    trigger="loop"
                    colors="primary:#4b38b3,secondary:#08a88a"
                    style="width:50px;height:50px">
                </lord-icon>
                <div class="ms-3">
                    <h3 class="mb-0">ملف العميل: {{ $client->name }}</h3>
                    <p class="text-muted mb-0">
                        <span class="badge bg-{{ $client->status === 'active' ? 'success' : 'secondary' }}-subtle text-{{ $client->status === 'active' ? 'success' : 'secondary' }}">
                            {{ $client->status ?? '-' }}
                        </span>
                        <span class="ms-2">مسجل منذ {{ $client->created_at->diffForHumans() }}</span>
                    </p>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">المشاريع</h6>
                            <h3 class="mb-0">{{ $stats['projects'] }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <div class="avatar-title bg-primary-subtle text-primary rounded fs-4">
                                <i class="mdi mdi-briefcase-outline"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">العروض</h6>
                            <h3 class="mb-0">{{ $stats['offers'] }}</h3>
                            <p class="small text-muted mb-0">
                                <span class="text-success">{{ $stats['offers_open'] }} مفتوحة</span> | 
                                <span class="text-secondary">{{ $stats['offers_closed'] }} مغلقة</span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <div class="avatar-title bg-info-subtle text-info rounded fs-4">
                                <i class="mdi mdi-file-document-outline"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">إجمالي التحصيلات</h6>
                            <h3 class="mb-0">{{ number_format($stats['collections_sum'], 2) }} ج.م</h3>
                        </div>
                        <div class="avatar-sm">
                            <div class="avatar-title bg-success-subtle text-success rounded fs-4">
                                <i class="mdi mdi-cash"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">حالة العميل</h6>
                            <h3 class="mb-0">{{ $client->status ?? '-' }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <div class="avatar-title {{ $client->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded fs-4">
                                <i class="mdi mdi-check-circle-outline"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs nav-tabs-custom mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $tab==='overview' ? 'active' : '' }}" wire:click.prevent="setTab('overview')">
                <i class="mdi mdi-information-outline me-1"></i> نظرة عامة
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab==='projects' ? 'active' : '' }}" wire:click.prevent="setTab('projects')">
                <i class="mdi mdi-folder-outline me-1"></i> المشاريع
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab==='offers' ? 'active' : '' }}" wire:click.prevent="setTab('offers')">
                <i class="mdi mdi-file-document-edit-outline me-1"></i> العروض
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab==='transactions' ? 'active' : '' }}" wire:click.prevent="setTab('transactions')">
                <i class="mdi mdi-swap-horizontal-circle-outline me-1"></i> الحركات المالية
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab==='contacts' ? 'active' : '' }}" wire:click.prevent="setTab('contacts')">
                <i class="mdi mdi-card-account-phone-outline me-1"></i> التواصل
            </a>
        </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content">
        @if($tab === 'overview')
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="mdi mdi-card-account-details-outline me-1"></i> 
                            بيانات العميل الأساسية
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="border-0 text-muted" width="30%">الاسم الكامل</td>
                                        <td class="border-0 fw-semibold">{{ $client->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border-0 text-muted">البريد الإلكتروني</td>
                                        <td class="border-0">{{ $client->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border-0 text-muted">رقم الهاتف</td>
                                        <td class="border-0">{{ $client->phone ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border-0 text-muted">الدولة</td>
                                        <td class="border-0">{{ optional($client->countryRelation)->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border-0 text-muted">العنوان</td>
                                        <td class="border-0">{{ $client->address ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border-0 text-muted">تاريخ التسجيل</td>
                                        <td class="border-0">{{ optional($client->created_at)->format('Y-m-d') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="mdi mdi-clipboard-account-outline me-1"></i> 
                            معلومات التواصل
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="border-0 text-muted" width="30%">اسم المسؤول</td>
                                        <td class="border-0">{{ $client->contact_name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border-0 text-muted">وظيفة المسؤول</td>
                                        <td class="border-0">{{ $client->contact_job ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border-0 text-muted">هاتف المسؤول</td>
                                        <td class="border-0">{{ $client->contact_phone ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border-0 text-muted">بريد المسؤول</td>
                                        <td class="border-0">{{ $client->contact_email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border-0 text-muted">جهة الاتصال الرئيسية</td>
                                        <td class="border-0">
                                            <span class="badge bg-{{ $client->is_main_contact ? 'success' : 'secondary' }}-subtle text-{{ $client->is_main_contact ? 'success' : 'secondary' }}">
                                                {{ $client->is_main_contact ? 'نعم' : 'لا' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @elseif($tab === 'projects')
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                    <h5 class="mb-3 mb-md-0">
                        <i class="mdi mdi-folder-outline me-1"></i> 
                        مشاريع العميل
                    </h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" placeholder="بحث..." wire:model.debounce.500ms="projectSearch">
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="50px">#</th>
                                <th>اسم المشروع</th>
                                <th width="120px">النوع</th>
                                <th width="120px">الحالة</th>
                                <th width="120px">تاريخ البداية</th>
                                <th width="120px">تاريخ النهاية</th>
                                <th width="100px">الأولوية</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $i => $p)
                            <tr>
                                <td>{{ $projects->firstItem() + $i }}</td>
                                <td>
                                    <a href="#" class="text-decoration-none">{{ $p->name }}</a>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info">{{ $p->project_type ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $p->status === 'مكتمل' ? 'success' : ($p->status === 'قيد التنفيذ' ? 'warning' : 'secondary') }}-subtle text-{{ $p->status === 'مكتمل' ? 'success' : ($p->status === 'قيد التنفيذ' ? 'warning' : 'secondary') }}">
                                        {{ $p->status ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ optional($p->start_date)->format('Y-m-d') }}</td>
                                <td>{{ optional($p->end_date)->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge bg-{{ $p->priority === 'عالي' ? 'danger' : ($p->priority === 'متوسط' ? 'warning' : 'secondary') }}-subtle text-{{ $p->priority === 'عالي' ? 'danger' : ($p->priority === 'متوسط' ? 'warning' : 'secondary') }}">
                                        {{ $p->priority ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <lord-icon
                                        src="{{URL::asset('assets/images/icon/empty-folder.json')}}"
                                        trigger="loop"
                                        colors="primary:#ccc,secondary:#eee"
                                        style="width:80px;height:80px">
                                    </lord-icon>
                                    <p class="mt-2">لا توجد مشاريع مسجلة لهذا العميل</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($projects->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        عرض <span class="fw-semibold">{{ $projects->firstItem() }}</span> إلى 
                        <span class="fw-semibold">{{ $projects->lastItem() }}</span> من 
                        <span class="fw-semibold">{{ $projects->total() }}</span> نتيجة
                    </div>
                    <div>
                        {{ $projects->onEachSide(1)->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
        @elseif($tab === 'offers')
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                    <h5 class="mb-3 mb-md-0">
                        <i class="mdi mdi-file-document-edit-outline me-1"></i> 
                        عروض الأسعار
                    </h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" placeholder="بحث..." wire:model.debounce.500ms="offerSearch">
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="50px">#</th>
                                <th>المشروع</th>
                                <th width="150px" class="text-end">المبلغ</th>
                                <th width="120px">الحالة</th>
                                <th width="120px">تاريخ البداية</th>
                                <th width="120px">تاريخ النهاية</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($offers as $i => $o)
                            <tr>
                                <td>{{ $offers->firstItem() + $i }}</td>
                                <td>
                                    <a href="#" class="text-decoration-none">{{ optional($o->project)->name ?? '-' }}</a>
                                </td>
                                <td class="text-end fw-bold">{{ number_format((float)$o->amount,2) }} ج.م</td>
                                <td>
                                    <span class="badge bg-{{ $o->status === 'مقبول' ? 'success' : ($o->status === 'مرفوض' ? 'danger' : 'warning') }}-subtle text-{{ $o->status === 'مقبول' ? 'success' : ($o->status === 'مرفوض' ? 'danger' : 'warning') }}">
                                        {{ $o->status ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ optional($o->start_date)->format('Y-m-d') }}</td>
                                <td>{{ optional($o->end_date)->format('Y-m-d') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <lord-icon
                                        src="{{URL::asset('assets/images/icon/empty-document.json')}}"
                                        trigger="loop"
                                        colors="primary:#ccc,secondary:#eee"
                                        style="width:80px;height:80px">
                                    </lord-icon>
                                    <p class="mt-2">لا توجد عروض مسجلة لهذا العميل</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($offers->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        عرض <span class="fw-semibold">{{ $offers->firstItem() }}</span> إلى 
                        <span class="fw-semibold">{{ $offers->lastItem() }}</span> من 
                        <span class="fw-semibold">{{ $offers->total() }}</span> نتيجة
                    </div>
                    <div>
                        {{ $offers->onEachSide(1)->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
        @elseif($tab === 'transactions')
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                    <h5 class="mb-3 mb-md-0">
                        <i class="mdi mdi-swap-horizontal-circle-outline me-1"></i> 
                        الحركات المالية
                    </h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" placeholder="بحث..." wire:model.debounce.500ms="trxSearch">
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="50px">#</th>
                                <th width="120px">النوع</th>
                                <th width="150px" class="text-end">المبلغ</th>
                                <th>البند</th>
                                <th>الحساب</th>
                                <th width="150px">نوع التحصيل</th>
                                <th width="120px">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $i => $t)
                            <tr>
                                <td>{{ $transactions->firstItem() + $i }}</td>
                                <td>
                                    <span class="badge bg-{{ $t->type==='مصروفات' ? 'danger' : 'success' }}-subtle text-{{ $t->type==='مصروفات' ? 'danger' : 'success' }}">
                                        {{ $t->type }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold {{ $t->type==='مصروفات' ? 'text-danger' : 'text-success' }}">
                                    {{ number_format((float)$t->amount, 2) }} ج.م
                                </td>
                                <td>{{ optional($t->item)->name ?? '-' }}</td>
                                <td>{{ optional($t->account)->name ?? '-' }}</td>
                                <td>
                                    @if($t->type === 'تحصيل')
                                        <span class="badge bg-primary-subtle text-primary">{{ $t->collection_type ?? 'أخرى' }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ optional($t->transaction_date)->format('Y-m-d') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <lord-icon
                                        src="{{URL::asset('assets/images/icon/empty-transaction.json')}}"
                                        trigger="loop"
                                        colors="primary:#ccc,secondary:#eee"
                                        style="width:80px;height:80px">
                                    </lord-icon>
                                    <p class="mt-2">لا توجد حركات مالية مسجلة لهذا العميل</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($transactions->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        عرض <span class="fw-semibold">{{ $transactions->firstItem() }}</span> إلى 
                        <span class="fw-semibold">{{ $transactions->lastItem() }}</span> من 
                        <span class="fw-semibold">{{ $transactions->total() }}</span> نتيجة
                    </div>
                    <div>
                        {{ $transactions->onEachSide(1)->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
        @elseif($tab === 'contacts')
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-4">
                    <i class="mdi mdi-card-account-phone-outline me-1"></i> 
                    معلومات التواصل
                </h5>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">بيانات المسؤول</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="small text-muted">اسم المسؤول</div>
                                            <div class="fw-semibold">{{ $client->contact_name ?? '-' }}</div>
                                        </div>
                                        <span class="mdi mdi-account-outline text-primary fs-4"></span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="small text-muted">وظيفة المسؤول</div>
                                            <div class="fw-semibold">{{ $client->contact_job ?? '-' }}</div>
                                        </div>
                                        <span class="mdi mdi-briefcase-outline text-info fs-4"></span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="small text-muted">هاتف المسؤول</div>
                                            <div class="fw-semibold">{{ $client->contact_phone ?? '-' }}</div>
                                        </div>
                                        <span class="mdi mdi-phone-outline text-success fs-4"></span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="small text-muted">بريد المسؤول</div>
                                            <div class="fw-semibold">{{ $client->contact_email ?? '-' }}</div>
                                        </div>
                                        <span class="mdi mdi-email-outline text-warning fs-4"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">معلومات إضافية</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="small text-muted">جهة الاتصال الرئيسية</div>
                                            <div class="fw-semibold">{{ $client->is_main_contact ? 'نعم' : 'لا' }}</div>
                                        </div>
                                        <span class="mdi mdi-check-circle-outline {{ $client->is_main_contact ? 'text-success' : 'text-muted' }} fs-4"></span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="small text-muted">العنوان</div>
                                            <div class="fw-semibold">{{ $client->address ?? '-' }}</div>
                                        </div>
                                        <span class="mdi mdi-map-marker-outline text-danger fs-4"></span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                      
                                        <span class="mdi mdi-earth text-primary fs-4"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>