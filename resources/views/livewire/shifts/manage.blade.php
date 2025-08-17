<div class="container-fluid p-4">
    <!-- فورم إضافة/تعديل شيفت - تصميم محسن -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="mdi mdi-clock-edit-outline me-2"></i>
                {{ $shift_id ? 'تعديل الشيفت' : 'إضافة شيفت جديد' }}
            </h5>
            <div class="badge bg-primary rounded-pill">
                <i class="mdi mdi-account-group me-1"></i>
                {{ count($employee_ids) }} موظف مختار
            </div>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="save" class="needs-validation" novalidate>
                <div class="row g-3">
                    <!-- حقل اسم الشيفت -->
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-bold">اسم الشيفت</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="mdi mdi-rename-box"></i></span>
                            <input type="text" wire:model="name" id="name" class="form-control" placeholder="أدخل اسم الشيفت">
                        </div>
                        @error('name') <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <!-- حقل الأيام -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">أيام العمل</label>
                        <div class="days-checkbox-container p-3 rounded bg-light">
                            @foreach(['saturday','sunday','monday','tuesday','wednesday','thursday','friday'] as $day)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" 
                                           wire:model="days" id="day-{{ $day }}" value="{{ $day }}">
                                    <label class="form-check-label" for="day-{{ $day }}">
                                        {{ trans("days.".$day) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('days') <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <!-- حقول الوقت -->
                    <div class="col-md-3">
                        <label for="start_time" class="form-label fw-bold">وقت البدء</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="mdi mdi-clock-start"></i></span>
                            <input type="time" wire:model="start_time" id="start_time" class="form-control">
                        </div>
                        @error('start_time') <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="end_time" class="form-label fw-bold">وقت الانتهاء</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="mdi mdi-clock-end"></i></span>
                            <input type="time" wire:model="end_time" id="end_time" class="form-control">
                        </div>
                        @error('end_time') <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <!-- حقل مدة الإجازات -->
                    <div class="col-md-3">
                        <label for="leave_allowance" class="form-label fw-bold">مدة السماح بالإجازات (أيام)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="mdi mdi-calendar-account"></i></span>
                            <input type="number" wire:model="leave_allowance" id="leave_allowance" class="form-control" min="0">
                        </div>
                        @error('leave_allowance') <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <!-- حقل الموظفين -->
                    <div class="col-md-12">
                        <label class="form-label fw-bold">اختيار الموظفين</label>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-2">
                                <select wire:model="employee_ids" multiple class="form-select h-auto" style="min-height: 150px;">
                                    @foreach($allEmployees as $employee)
                                        <option value="{{ $employee->id }}" class="p-2">
                                            <div class="d-flex align-items-center">
                                                @if($employee->avatar)
                                                    <img src="{{ asset('storage/'.$employee->avatar) }}" class="rounded-circle me-2" width="30" height="30">
                                                @else
                                                    <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:30px;height:30px;">
                                                        {{ substr($employee->full_name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <span>{{ $employee->full_name }}</span>
                                            </div>
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @error('employee_ids') <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <!-- أزرار الإجراءات -->
                    <div class="col-md-12 text-end mt-3">
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

    <!-- جدول الشيفتات - تصميم محسن -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0 fw-bold">
                <i class="mdi mdi-clock-outline me-2"></i>
                قائمة الشيفتات
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold">الاسم</th>
                            <th class="fw-bold">الأيام</th>
                            <th class="fw-bold">من</th>
                            <th class="fw-bold">إلى</th>
                            <th class="fw-bold">مدة الإجازات</th>
                            <th class="fw-bold">الموظفين</th>
                            <th class="fw-bold text-center">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shifts as $shift)
                            <tr>
                                <td class="fw-semibold">{{ $shift->name }}</td>
                                <td>
                                    @foreach($shift->days as $day)
                                        <span class="badge bg-primary me-1 mb-1">{{ trans("days.".$day) }}</span>
                                    @endforeach
                                </td>
                                <td>{{ date('h:i A', strtotime($shift->start_time)) }}</td>
                                <td>{{ date('h:i A', strtotime($shift->end_time)) }}</td>
                                <td>{{ $shift->leave_allowance }} يوم</td>
                                <td>
                                    @if($shift->employees->count())
                                        <div class="avatar-group">
                                            @foreach($shift->employees->take(5) as $employee)
                                                <div class="avatar-sm" data-bs-toggle="tooltip" title="{{ $employee->full_name }}">
                                                    @if($employee->avatar)
                                                        <img src="{{ asset('storage/'.$employee->avatar) }}" class="rounded-circle border">
                                                    @else
                                                        <div class="avatar bg-primary text-white rounded-circle">
                                                            {{ substr($employee->full_name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                            @if($shift->employees->count() > 5)
                                                <div class="avatar-sm bg-light rounded-circle d-inline-flex align-items-center justify-content-center">
                                                    +{{ $shift->employees->count() - 5 }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">لا يوجد موظفين</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button wire:click="edit({{ $shift->id }})" class="btn btn-sm btn-outline-primary me-1" title="تعديل">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button wire:click="delete({{ $shift->id }})" class="btn btn-sm btn-outline-danger" title="حذف">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="mdi mdi-clock-alert-outline fs-1"></i>
                                    <p class="mt-2">لا توجد شيفتات مضافة</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .days-checkbox-container {
        border: 1px solid #dee2e6;
    }
    
    .form-check-inline {
        margin-right: 15px;
        margin-bottom: 8px;
    }
    
    .avatar {
        width: 30px;
        height: 30px;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-group {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    
    .avatar-sm {
        width: 30px;
        height: 30px;
    }
    
    .avatar-sm img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    select[multiple] option {
        padding: 8px 12px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    select[multiple] option:hover {
        background-color: #f8f9fa;
    }
    
    .badge {
        font-weight: 500;
        padding: 5px 10px;
    }
    
    .table th {
        white-space: nowrap;
        vertical-align: middle;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تهيئة الأدوات المساعدة
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>