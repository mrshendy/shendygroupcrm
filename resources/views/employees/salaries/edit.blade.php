@extends('layouts.master')

@section('title', 'تعديل المرتب')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">تحديث بيانات المرتب</h4>
            @livewire('employees.salaries.edit', ['salaryId' => $id])
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('salaryAdded', () => {
                alert('تم إضافة مرتب جديد بنجاح!');
            });

            Livewire.on('salaryUpdated', () => {
                alert('تم تحديث بيانات المرتب بنجاح!');
            });

            Livewire.on('salaryDeleted', () => {
                alert('تم حذف المرتب بنجاح!');
            });
        });
    </script>
@endsection
