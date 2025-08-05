@extends('layouts.master')

@section('title', 'قائمة الموظفين')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">إضافة موظفين
            @livewire('employees.create')
        </div>
    </div>
@endsection
@section('scripts')
   <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('clientAdded', () => {
                alert('تم إضافة الموظف جديد بنجاح!');
            });

            Livewire.on('clientUpdated', () => {
                alert('تم تحديث بيانات الموظف بنجاح!');
            });

            Livewire.on('clientDeleted', () => {
                alert('تم حذف الموظف بنجاح!');
            });
        });
    </script>