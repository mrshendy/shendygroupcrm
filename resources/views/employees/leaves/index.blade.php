@extends('layouts.master')

@section('title', 'إدارة الإجازات')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>قائمة الإجازات</h4>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#leaveModal">
                <i class="mdi mdi-plus-circle-outline"></i> طلب إجازة جديدة
            </button>
        </div>

        <!-- جدول الإجازات -->
        @livewire('employees.leaves.index')
    </div>
</div>

<!-- Modal لطلب إجازة جديدة -->
<div wire:ignore.self class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="leaveModalLabel">طلب إجازة جديدة</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
        </div>
        <div class="modal-body">
            @livewire('employees.leaves.create')
        </div>
    </div>
  </div>
</div>
@endsection
