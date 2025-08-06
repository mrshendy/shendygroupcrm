@extends('layouts.master')

@section('title', 'الحسابات المالية')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">قائمة الحسابات المالية</h4>
@livewire('finance.accounts.manage')
        </div>
    </div>
@endsection
@section('scripts')
    <script>
         window.addEventListener('itemAdded', function () {
    alert('تم إضافة الحساب جديد بنجاح!');
});

window.addEventListener('itemUpdated', function () {
    alert('تم تحديث بيانات الحساب بنجاح!');
});

window.addEventListener('itemDeleted', function () {
    alert('تم حذف الحساب بنجاح!');
});
        
    </script>