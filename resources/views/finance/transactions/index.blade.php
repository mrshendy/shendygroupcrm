@extends('layouts.master')

@section('title', 'إدارة المصروفات والتحصيلات')
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">قائمة الحسابات المالية</h4>
            @livewire('finance.transactions.index')
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('accountAdded', () => {
                alert('تم إضافة حساب جديد بنجاح!');
            });

            Livewire.on('accountUpdated', () => {
                alert('تم تحديث بيانات الحساب بنجاح!');
            });

            Livewire.on('accountDeleted', () => {
                alert('تم حذف الحساب بنجاح!');
            });
        });
        
    </script>