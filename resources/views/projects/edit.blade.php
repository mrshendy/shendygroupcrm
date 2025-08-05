@extends('layouts.master')

@section('title', 'قائمة العملاء')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('projects.edit', ['project' => $id])
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('clientAdded', () => {
                alert('تم إضافة عميل جديد بنجاح!');
            });

            Livewire.on('clientUpdated', () => {
                alert('تم تحديث بيانات العميل بنجاح!');
            });

            Livewire.on('clientDeleted', () => {
                alert('تم حذف العميل بنجاح!');
            });
        });
    </script>