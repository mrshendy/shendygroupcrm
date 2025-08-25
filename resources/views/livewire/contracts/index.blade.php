<div class="" dir="rtl">
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
                            <td>{{ $c->type }}</td>
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
                                <span class="badge bg-secondary">{{ $c->status }}</span>
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
            </div>
        </div>
    @empty
        <div class="alert alert-info">لا توجد عقود مطابقة.</div>
    @endforelse

    <div class="d-flex justify-content-center mt-4">
        {{ $contracts->links() }}
    </div>

    <!-- Modal تأكيد الحذف (داخل نفس الجذر) -->
    <div wire:ignore.self class="modal fade" id="deleteConfirmModal" tabindex="-1"
         aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header border-0">
            <h5 class="modal-title text-danger">
              <i class="mdi mdi-alert-outline me-2"></i> تأكيد الحذف
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                    wire:click="cancelDelete"></button>
          </div>
          <div class="modal-body">
            هل أنت متأكد من حذف هذا العقد؟ لا يمكن التراجع عن هذه العملية.
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                    wire:click="cancelDelete">إلغاء</button>
            <button type="button"
                    class="btn btn-danger"
                    wire:click="deleteConfirmed"
                    wire:loading.attr="disabled"
                    wire:target="deleteConfirmed">
              <span wire:loading.remove wire:target="deleteConfirmed">نعم، احذف</span>
              <span wire:loading wire:target="deleteConfirmed">
                  <span class="spinner-border spinner-border-sm"></span> جارٍ الحذف...
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>
</div>


<script>
    (function () {
        const modalEl = document.getElementById('deleteConfirmModal');
        let modal = null;

        window.addEventListener('contracts-open-delete', () => {
            if (!modal) modal = new bootstrap.Modal(modalEl);
            modal.show();
        });

        window.addEventListener('contracts-close-delete', () => {
            if (!modal) modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.hide();
        });
    })();
</script>
