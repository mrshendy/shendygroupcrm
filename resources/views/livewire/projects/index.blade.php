<div class="projects-management-container">
    <!-- Header Section -->
    <div class="management-header mb-4">
        <h2 class="page-title">
            <i class="mdi mdi-clipboard-flow-outline me-2"></i>إدارة المشاريع
        </h2>
        <div class="d-flex justify-content-between align-items-center">
            <!-- Search Box -->
            <div class="search-box">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="mdi mdi-clipboard-search text-muted"></i>
                    </span>
                    <input wire:model.debounce.300ms="search" class="form-control border-start-0"
                        placeholder="ابحث باسم المشروع أو الوصف...">
                </div>
            </div>
            <!-- Add Project Button -->
            @can('project-create')
                <a href="{{ route('projects.create') }}" class="btn btn-primary-gradient">
                    <i class="mdi mdi-plus-circle me-2"></i>إضافة مشروع جديد
                </a>
            @endcan
        </div>
    </div>

    <!-- Projects Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">اسم المشروع</th>
                            <th>النوع</th>
                            <th>العميل</th>
                            <th>الدولة</th>
                            <th>المدة</th>
                            <th>الأولوية</th>
                            <th>الحالة</th>
                            <th class="text-end pe-4">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr class="align-middle">
                                <td class="ps-4 fw-semibold">
                                    <i class="mdi mdi-folder-outline text-primary me-2"></i>
                                    {{ $project->name }}
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-dark">
                                        {{ $project->project_type }}
                                    </span>
                                </td>
                                <td><span class="text-muted">{{ $project->client->name ?? '-' }}</span></td>
                                <td><span class="text-muted">{{ $project->country->name ?? '-' }}</span></td>
                                <td>
                                    @if ($project->start_date && $project->end_date)
                                        <span class="text-muted small">
                                            {{ $project->start_date->format('Y-m-d') }} →
                                            {{ $project->end_date->format('Y-m-d') }}
                                        </span>
                                        @if ($project->end_date->diffInDays(now(), false) <= 3 && $project->end_date >= now())
                                            <span class="badge bg-warning bg-opacity-25 text-warning ms-1">
                                                <i class="mdi mdi-alert-outline me-1"></i>قرب الانتهاء
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $priorityColors = [
                                            'low' => 'bg-secondary text-dark',
                                            'medium' => 'bg-info text-white',
                                            'high' => 'bg-danger text-white',
                                            'critical' => 'bg-dark text-white',
                                        ];
                                    @endphp
                                    <span
                                        class="badge {{ $priorityColors[$project->priority] ?? 'bg-light text-dark' }}">
                                        {{ $project->priority }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusBadges = [
                                            'new' => ['text-danger', 'mdi-new-box', 'جديد'],
                                            'in_progress' => ['text-warning', 'mdi-reload', 'جاري العمل'],
                                            'completed' => ['text-success', 'mdi-check-circle-outline', 'مكتمل'],
                                            'closed' => ['text-danger', 'mdi-close-circle-outline', 'مغلق'],
                                        ];
                                        [$color, $icon, $label] = $statusBadges[$project->status] ?? [
                                            'text-muted',
                                            'mdi-help',
                                            $project->status,
                                        ];
                                    @endphp
                                    <span class="badge bg-opacity-10 {{ $color }}">
                                        <i class="mdi {{ $icon }} me-1"></i>{{ $label }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group" role="group">
                                        @can('project-show')
                                            <a href="{{ route('projects.show', $project->id) }}"
                                                class="btn btn-sm btn-outline-secondary" title="عرض التفاصيل">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        @endcan
                                        @can('project-edit')
                                            <a href="{{ route('projects.edit', $project->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="تعديل">
                                                <i class="mdi mdi-tooltip-edit-outline"></i>
                                            </a>
                                        @endcan

                                          @can('project-delete')
                            <button wire:click="confirmDelete({{ $project->id }})" 
                                class="btn btn-sm btn-outline-danger">
                                <i class="mdi mdi-trash-can"></i> 
                            </button>
                        @endcan

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">لا توجد مشاريع متاحة</h5>
                                        <p class="text-muted mb-4">يمكنك البدء بإضافة مشروع جديد</p>
                                        @can('project-create')
                                            <a href="{{ route('projects.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>إضافة مشروع
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($projects->hasPages())
                <div class="card-footer bg-transparent border-top-0 pt-3 pb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            عرض <span class="fw-semibold">{{ $projects->firstItem() }}</span>
                            إلى <span class="fw-semibold">{{ $projects->lastItem() }}</span>
                            من <span class="fw-semibold">{{ $projects->total() }}</span> مشروع
                        </div>
                        {{ $projects->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
        <!-- نافذة التأكيد -->
    @if ($confirmingDelete)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,.5)">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">تأكيد الحذف</h5>
                        <button type="button" class="btn-close" wire:click="$set('confirmingDelete', false)"></button>
                    </div>
                    <div class="modal-body">
                        هل أنت متأكد أنك تريد حذف هذا المشروع؟
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="$set('confirmingDelete', false)">إلغاء</button>
                        <button class="btn btn-danger" wire:click="delete">تأكيد الحذف</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
