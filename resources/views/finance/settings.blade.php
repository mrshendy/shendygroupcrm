@extends('layouts.master')

@section('title', 'الحسابات المالية')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">قائمة الحسابات المالية</h4>
            @livewire('finance.settings')
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('accountAdded', function () {
                alert('✅ تم إضافة حساب جديد بنجاح!');
            });

            Livewire.on('accountUpdated', function () {
                alert('🔄 تم تحديث بيانات الحساب بنجاح!');
            });

            Livewire.on('accountDeleted', function () {
                alert('🗑️ تم حذف الحساب بنجاح!');
            });
        });
    </script>
@endsection
