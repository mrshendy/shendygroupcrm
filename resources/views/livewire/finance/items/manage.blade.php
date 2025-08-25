<div class="container-fluid px-4 py-3">

    {{-- العنوان والتنبيه --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="m-0">
            <span class="mdi mdi-finance me-2 text-primary"></span>
            إدارة البنود المالية
        </h5>
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-0">
                <span class="mdi mdi-check-circle-outline me-2"></span>
                {{ session('message') }}
                <button type="button" class="btn-close p-2" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- نموذج الإضافة أو التعديل --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light text-dark d-flex align-items-center">
            <span class="mdi mdi-plus-circle-outline me-2"></span>
            {{ $updateMode ? 'تعديل بند' : 'إضافة بند جديد' }}
        </div>
        <div class="card-body">
            <form wire:submit.prevent="{{ $updateMode ? 'update' : 'save' }}">
                <div class="row g-3">
                    {{-- اسم البند --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">اسم البند</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <span class="mdi mdi-tag-outline"></span>
                            </span>
                            <input type="text" class="form-control shadow-sm"
                                   wire:model="name"
                                   placeholder="أدخل اسم البند">
                        </div>
                        @error('name')
                            <small class="text-danger d-block mt-1">
                                <span class="mdi mdi-alert-circle-outline me-1"></span>
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    {{-- النوع --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">النوع</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <span class="mdi mdi-format-list-bulleted-type"></span>
                            </span>
                            <select class="form-select shadow-sm" wire:model="type">
                                <option value="">اختر النوع...</option>
                                <option value="مصروف">مصروف</option>
                                <option value="إيراد">إيراد</option>
                            </select>
                        </div>
                        @error('type')
                            <small class="text-danger d-block mt-1">
                                <span class="mdi mdi-alert-circle-outline me-1"></span>
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    {{-- الحالة --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">الحالة</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <span class="mdi mdi-power-plug-outline"></span>
                            </span>
                            <select class="form-select shadow-sm" wire:model="status">
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                            </select>
                        </div>
                        @error('status')
                            <small class="text-danger d-block mt-1">
                                <span class="mdi mdi-alert-circle-outline me-1"></span>
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>

                {{-- أزرار الحفظ/التحديث --}}
                <div class="mt-4 d-flex justify-content-between align-items-center border-top pt-4">
                    <div>
                        @if($updateMode)
                            <button type="button" class="btn btn-outline-danger px-4" wire:click="cancelUpdate">
                                <span class="mdi mdi-close-circle-outline me-1"></span>
                                إلغاء
                            </button>
                        @endif
                    </div>
                    <div>
                        <button type="submit" class="btn btn-{{ $updateMode ? 'success' : 'primary' }} px-4">
                            <span class="mdi mdi-content-save{{ $updateMode ? '-edit' : '' }}-outline me-1"></span>
                            {{ $updateMode ? 'تحديث' : 'حفظ' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- جدول عرض البنود --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- البحث + عدد السجلات --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="input-group w-25">
                    <span class="input-group-text bg-light">
                        <span class="mdi mdi-magnify"></span>
                    </span>
                    <input type="text" wire:model="search" class="form-control shadow-sm" placeholder="ابحث...">
                </div>
                <div class="text-muted small">
                    <span class="mdi mdi-counter me-1"></span>
                    العدد الإجمالي: {{ $items->total() }}
                </div>
            </div>

            {{-- الجدول --}}
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="40%">اسم البند</th>
                            <th width="25%">النوع</th>
                            <th width="20%">الحالة</th>
                            <th width="15%">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->type == 'إيراد' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $item->type == 'إيراد' ? 'success' : 'warning' }}">
                                        {{ $item->type }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $item->status == 'active' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $item->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ $item->status == 'active' ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        {{-- زر التعديل --}}
                                        <a href="{{ route('finance.items.edit', $item->id) }}" 
                                           class="btn btn-sm btn-outline-primary d-flex align-items-center">
                                            <i class="mdi mdi-pencil-outline me-1"></i> تعديل
                                        </a>

                                        {{-- زر الحذف --}}
                                        <button type="button" 
                                                wire:click="confirmDelete({{ $item->id }})" 
                                                class="btn btn-sm btn-outline-danger d-flex align-items-center">
                                            <i class="mdi mdi-delete-outline me-1"></i> حذف
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <span class="mdi mdi-database-remove-outline me-2"></span>
                                    لا توجد بيانات لعرضها
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- الصفحات --}}
            @if($items->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        عرض {{ $items->firstItem() }} إلى {{ $items->lastItem() }} من {{ $items->total() }}
                    </div>
                    {{ $items->links('vendor.pagination.bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title">
                    <i class="mdi mdi-alert-circle-outline me-2"></i> تأكيد الحذف
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                هل أنت متأكد أنك تريد حذف هذا البند؟ لا يمكن التراجع بعد الحذف.
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" wire:click="deleteConfirmed">
                    نعم، احذف
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('showDeleteModal', () => {
        let modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    });

    window.addEventListener('hideDeleteModal', () => {
        let modalEl = document.getElementById('deleteModal');
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    });
</script>
