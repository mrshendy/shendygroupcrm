@extends('layouts.master')

@section('title', 'قائمة الموظفين')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('employees.index')
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('employeeAdded', () => {
                alert('✅ تم إضافة الموظف بنجاح!');
            });

            Livewire.on('employeeUpdated', () => {
                alert('✏️ تم تحديث بيانات الموظف بنجاح!');
            });

            Livewire.on('employeeDeleted', () => {
                alert('🗑️ تم حذف الموظف بنجاح!');
            });
        });
    </script>
@endsection
