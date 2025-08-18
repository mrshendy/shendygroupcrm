<div class="container-fluid p-4" dir="rtl">
    {{-- تنبيه نجاح --}}
    @if (session()->has('success'))
        <div class="alert alert-success shadow-sm mb-3">{{ session('success') }}</div>
    @endif

    {{-- ===== بطاقة: نموذج إضافة/تعديل شيفت ===== --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="mdi mdi-clock-edit-outline me-2"></i>
                {{ $shift_id ? 'تعديل الشيفت' : 'إضافة شيفت جديد' }}
            </h5>
            <div class="badge bg-primary rounded-pill">
                <i class="mdi mdi-account-group me-1"></i>
                {{ is_array($employee_ids) ? count($employee_ids) : 0 }} موظف مختار
            </div>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="save" class="needs-validation" novalidate>
                <div class="row g-3">
                    {{-- اسم الشيفت --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-bold">اسم الشيفت</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="mdi mdi-rename-box"></i></span>
                            <input type="text" wire:model="name" id="name" class="form-control" placeholder="مثال: صباحي / مسائي">
                        </div>
                        @error('name') <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- أيام العمل كـ Chips عربية --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">أيام العمل</label>

                        @php
                            $daysKeys = ['saturday','sunday','monday','tuesday','wednesday','thursday','friday'];
                            // خريطة عربية احتياطية لو ملف الترجمة غير موجود
                            $daysMap  = [
                                'saturday'=>'السبت','sunday'=>'الأحد','monday'=>'الاثنين',
                                'tuesday'=>'الثلاثاء','wednesday'=>'الأربعاء',
                                'thursday'=>'الخميس','friday'=>'الجمعة',
                            ];
                            $labelFor = fn($d) => trans("days.$d") !== "days.$d" ? trans("days.$d") : ($daysMap[$d] ?? $d);
                        @endphp

                        <div class="days-grid bg-light p-3 rounded">
                            @foreach($daysKeys as $day)
                                <input class="btn-check" type="checkbox" id="day-{{ $day }}" wire:model="days" value="{{ $day }}">
                                <label class="btn btn-outline-primary btn-sm day-chip mb-2 me-2" for="day-{{ $day }}">
                                    {{ $labelFor($day) }}
                                </label>
                            @endforeach
                        </div>
                        @error('days') <div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                        <div class="small text-muted mt-2">
                            @if(!empty($days))
                                تم اختيار:
                                <strong>
                                    {{ collect($days)->map(fn($d) => $labelFor($d))->implode('، ') }}
                                </strong>
                            @else
                                لم يتم اختيار أيام بعد
                            @endif
                        </div>
                    </div>

                    {{-- وقت البدء --}}
                    <div class="col-md-3">
                        <label for="start_time" class="form-label fw-bold">وقت البدء</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="mdi mdi-clock-start"></i></span>
                            <input type="time" wire:model="start_time" id="start_time" class="form-control">
                        </div>
                        @error('start_time') <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- وقت الانتهاء --}}
                    <div class="col-md-3">
                        <label for="end_time" class="form-label fw-bold">وقت الانتهاء</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="mdi mdi-clock-end"></i></span>
                            <input type="time" wire:model="end_time" id="end_time" class="form-control">
                        </div>
                        @error('end_time') <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- مدة السماح بالإجازات (أيام) --}}
                    <div class="col-md-3">
                        <label for="leave_allowance" class="form-label fw-bold">مدة السماح بالإجازات (أيام)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="mdi mdi-calendar-account"></i></span>
                            <input type="number" min="0" wire:model="leave_allowance" id="leave_allowance" class="form-control" placeholder="مثال: 4">
                        </div>
                        @error('leave_allowance') <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- اختيار الموظفين (متعدد) + مربع بحث بسيط محلي --}}
                    <div class="col-md-12">
                        <label class="form-label fw-bold">اختيار الموظفين</label>

                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-sm-6">
                                        <input id="emp-filter" type="text" class="form-control" placeholder="ابحث بالاسم..."
                                               oninput="filterEmployees(this.value)">
                                    </div>
                                    <div class="col-sm-6 text-sm-end small text-muted">
                                        يمكنك اختيار أكثر من موظف بالضغط مع Ctrl/⌘
                                    </div>
                                </div>

                                <select id="employees-select" wire:model="employee_ids" multiple
                                        class="form-select h-auto" style="min-height: 200px;">
                                    @foreach($allEmployees as $employee)
                                        <option value="{{ $employee->id }}">
                                            {{ $employee->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @error('employee_ids') <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- الأزرار --}}
                    <div class="col-12 text-end mt-2">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="mdi mdi-content-save me-1"></i> {{ $shift_id ? 'تحديث' : 'حفظ' }}
                        </button>
                        @if($shift_id)
                            <button type="button" wire:click="resetForm" class="btn btn-outline-secondary px-4">
                                <i class="mdi mdi-close me-1"></i> إلغاء
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== بطاقة: جدول الشيفتات ===== --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0 fw-bold"><i class="mdi mdi-clock-outline me-2"></i> قائمة الشيفتات</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold">الاسم</th>
                            <th class="fw-bold">الأيام</th>
                            <th class="fw-bold">من</th>
                            <th class="fw-bold">إلى</th>
                            <th class="fw-bold">مدة الإجازات</th>
                            <th class="fw-bold">عدد الموظفين</th>
                            <th class="fw-bold text-center">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shifts as $shift)
                            @php
                                $labels = collect($shift->days ?? [])->map(fn($d) => $labelFor($d));
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $shift->name }}</td>
                                <td class="text-nowrap">
                                    @forelse($labels as $label)
                                        <span class="badge bg-light text-success border me-1 mb-1">{{ $label }}</span>
                                    @empty
                                        <span class="text-muted">—</span>
                                    @endforelse
                                </td>
                                <td>{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</td>
                                <td>{{ (int) $shift->leave_allowance }} يوم</td>
                                <td>{{ $shift->employees->count() }}</td>
                                <td class="text-center">
                                    <button wire:click="edit({{ $shift->id }})"
                                            class="btn btn-sm btn-outline-primary me-1" title="تعديل">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button wire:click="delete({{ $shift->id }})"
                                            class="btn btn-sm btn-outline-danger" title="حذف"
                                            onclick="return confirm('تأكيد حذف الشيفت؟');">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="mdi mdi-clock-alert-outline fs-1"></i>
                                    <p class="mt-2 mb-0">لا توجد شيفتات مضافة</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ===== أنماط مخصّصة ===== --}}
<style>
    .alert-success{
        background-color:rgba(79,214,156,.15);
        color:#2e8b6d;border:none;border-radius:8px
    }
    .card{border-radius:12px}
    .form-control,.form-select{border-radius:8px;border:1px solid #e0e0e0}
    .form-control:focus,.form-select:focus{border-color:#435ebe;box-shadow:0 0 0 .2rem rgba(67,94,190,.15)}
    .days-grid{border:1px solid #dee2e6;display:flex;flex-wrap:wrap;gap:.45rem}
    .day-chip{border-radius:999px;padding:.38rem .95rem;line-height:1}
    .btn-check:checked + .day-chip{color:#fff;background:#435ebe;border-color:#435ebe}
    .btn-check:focus + .day-chip,.day-chip:focus{box-shadow:0 0 0 .18rem rgba(67,94,190,.2)}
    .table thead{background:#f7f8fb}
    .table th,.table td{vertical-align:middle}
</style>

{{-- ===== فلترة محلية لقائمة الموظفين ===== --}}
<script>
    function filterEmployees(term){
        term = (term || '').toLowerCase();
        const select = document.getElementById('employees-select');
        if(!select) return;
        for (const opt of select.options){
            const text = (opt.text || '').toLowerCase();
            opt.hidden = term && !text.includes(term);
        }
    }
</script>
