<div class="container" dir="rtl">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-secondary">
            <i class="mdi mdi-file-document-multiple-outline me-2"></i>قائمة العقود
        </h3>
        <div class="d-flex gap-2">
            @can('contract-create')
            <a href="{{ route('contracts.create') }}" class="btn btn-primary btn-sm rounded-pill">
                <i class="mdi mdi-plus-circle-outline me-1"></i> عقد جديد 
            </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center">
            <i class="mdi mdi-check-circle-outline me-2 fs-4"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center">
            <i class="mdi mdi-alert-circle-outline me-2 fs-4"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- فلاتر --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="mdi mdi-magnify"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" placeholder="بحث بالرقم أو اسم العميل"
                           wire:model.debounce.500ms="search">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="mdi mdi-format-list-bulleted-type"></i>
                    </span>
                    <select class="form-select" wire:model="type">
                        <option value="">كل أنواع العقود</option>
                        @foreach($types as $k => $v)
                            <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="mdi mdi-alert-circle-outline"></i>
                    </span>
                    <select class="form-select" wire:model="status">
                        <option value="">كل الحالات</option>
                        <option value="draft">مسودة</option>
                        <option value="active">ساري</option>
                        <option value="suspended">موقوف</option>
                        <option value="completed">مكتمل</option>
                        <option value="cancelled">ملغي</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="mdi mdi-text-box-multiple-outline"></i>
                    </span>
                    <select class="form-select" wire:model="perPage">
                        <option value="10">10 / صفحة</option>
                        <option value="20">20 / صفحة</option>
                        <option value="50">50 / صفحة</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- قائمة العقود --}}
    @forelse($contracts as $c)
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light-primary d-flex justify-content-between align-items-center py-3 border-0">
                <div class="d-flex align-items-center">
                    <i class="mdi mdi-file-document-outline me-2 fs-4 text-primary"></i>
                    <span class="fw-bold">
                        عقد #{{ $c->id }} — {{ $c->client?->name ?? 'بدون عميل' }}
                    </span>
                </div>
                <div class="d-flex gap-2">
                    @can('contract-show')
                    <a href="{{ route('contracts.show', $c) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                        <i class="mdi mdi-eye-outline me-1"></i> عرض 
                    </a>
                    @endcan
                    @can('contract-edit')
                    <a href="{{ route('contracts.edit', $c) }}" class="btn btn-sm btn-outline-warning rounded-pill">
                        <i class="mdi mdi-pencil-outline me-1"></i> تعديل 
                    </a>
                    @endcan
                    @can('contract-delete')
                    <button class="btn btn-sm btn-outline-danger rounded-pill"
                            wire:click="confirmDelete({{ $c->id }})">
                        <i class="mdi mdi-delete-outline me-1"></i> حذف
                    </button>
                    @endcan
                </div>
            </div>

            <div class="card-body pt-3">
                {{-- جدول بيانات العقد --}}
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-hover">
                        <tbody>
                        <tr>
                            <th style="width:200px" class="bg-light">العميل</th>
                            <td>{{ $c->client?->name }}</td>
                            <th class="bg-light">نوع العقد</th>
                            <td>
                                @php
                                    $typeLabels = [
                                        'software' => 'برمجيات',
                                        'service'  => 'خدمات',
                                        'consult'  => 'استشارات',
                                        'other'    => 'أخرى'
                                    ];
                                @endphp
                                {{ $typeLabels[$c->type] ?? $c->type }}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">المشروع</th>
                            <td>{{ $c->project?->name ?? '—' }}</td>
                            <th class="bg-light">العرض</th>
                            <td>{{ $c->offer ? ('#'.$c->offer->id) : '—' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">الفترة</th>
                            <td>
                                <span class="badge bg-light text-dark">{{ optional($c->start_date)->format('Y-m-d') }}</span>
                                <i class="mdi mdi-arrow-left-thin mx-1"></i>
                                <span class="badge bg-light text-dark">{{ optional($c->end_date)->format('Y-m-d') }}</span>
                            </td>
                            <th class="bg-light">الحالة</th>
                            <td>
                                @php
                                    $statusLabels = [
                                        'draft'     => 'مسودة',
                                        'active'    => 'ساري',
                                        'suspended' => 'موقوف',
                                        'completed' => 'مكتمل',
                                        'cancelled' => 'ملغي'
                                    ];
                                    $statusColors = [
                                        'draft' => 'secondary',
                                        'active' => 'success',
                                        'suspended' => 'warning',
                                        'completed' => 'info',
                                        'cancelled' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$c->status] ?? 'secondary' }}">
                                    {{ $statusLabels[$c->status] ?? $c->status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">الإجمالي</th>
                            <td>
                                <span class="fw-bold">{{ number_format($c->amount,2) }} ج.م</span>
                                <small class="text-muted">{{ $c->include_tax ? '(شامل الضريبة)' : '' }}</small>
                            </td>
                            <th class="bg-light">الملاحظات</th>
                            <td>{{ $c->notes ?? '—' }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                {{-- بنود العقد --}}
                <div class="mb-4">
                    <h6 class="fw-bold d-flex align-items-center mb-3">
                        <i class="mdi mdi-format-list-checks me-2 text-primary"></i> بنود العقد
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>الترتيب</th>
                                <th>العنوان</th>
                                <th>النص</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($c->items as $idx => $it)
                                <tr>
                                    <td>{{ $idx+1 }}</td>
                                    <td>{{ $it->sort_order }}</td>
                                    <td>{{ $it->title }}</td>
                                    <td class="text-muted">{{ Str::limit($it->body, 50) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">لا توجد بنود</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- دفعات العقد --}}
                <div class="mb-2">
                    <h6 class="fw-bold d-flex align-items-center mb-3">
                        <i class="mdi mdi-credit-card-outline me-2 text-primary"></i> دفعات العقد
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>النوع</th>
                                <th>العنوان</th>
                                <th>المرحلة</th>
                                <th>شرط التحصيل</th>
                                <th>تاريخ الاستحقاق</th>
                                <th>المبلغ</th>
                                <th>ضريبة</th>
                                <th>مدفوعة؟</th>
                                <th>ملاحظات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($c->payments as $i => $p)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>
                                        @php
                                            $payTypes = ['milestone' => 'مرحلية', 'monthly' => 'شهرية'];
                                        @endphp
                                        {{ $payTypes[$p->payment_type] ?? $p->payment_type }}
                                    </td>
                                    <td>{{ $p->title }}</td>
                                    <td>{{ $p->stage ? \App\Models\ContractPayment::STAGES[$p->stage] : '—' }}</td>
                                    <td>
                                        @php
                                            $condLabels = ['date' => 'بالتاريخ', 'stage' => 'بالمرحلة'];
                                        @endphp
                                        {{ $condLabels[$p->condition] ?? $p->condition }}
                                    </td>
                                    <td>{{ optional($p->due_date)->format('Y-m-d') }}</td>
                                    <td class="fw-bold">{{ number_format($p->amount,2) }} ج.م</td>
                                    <td>{!! $p->include_tax ? '<i class="mdi mdi-check text-success"></i>' : '<i class="mdi mdi-close text-danger"></i>' !!}</td>
                                    <td>
                                        @if($p->is_paid)
                                            <span class="badge bg-success">مدفوعة</span>
                                        @else
                                            <span class="badge bg-warning text-dark">غير مدفوعة</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ Str::limit($p->notes, 20) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-3">لا توجد دفعات</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">لا توجد عقود مطابقة.</div>
    @endforelse

    <div class="d-flex justify-content-center mt-4">
        {{ $contracts->links() }}
    </div>
</div>

<!-- Modal تأكيد الحذف -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title text-danger">
          <i class="mdi mdi-alert-outline me-2"></i> تأكيد الحذف
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        هل أنت متأكد من حذف هذا العقد؟ لا يمكن التراجع عن هذه العملية.
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
        <button type="button" class="btn btn-danger" wire:click="delete">
          نعم، احذف
        </button>
      </div>
    </div>
  </div>
</div>

<script>
    window.addEventListener('open-delete-modal', () => {
        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    });

    window.addEventListener('close-delete-modal', () => {
        const modalEl = document.getElementById('deleteConfirmModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    });

    window.addEventListener('offer-deleted', () => {
        alert("✅ تم حذف العرض بنجاح");
    });
</script>

