@extends('layouts.master')

@section('title', ' تعديل الحسابات المالية')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">تعديل الحسابات المالية</h4>

    <livewire:finance.accounts.edit :id="$id" />
        </div>
    </div>
@endsection
@section('scripts')
    <script>
         window.addEventListener('accountAdded', function () {
    alert('تم إضافة الحساب جديد بنجاح!');
});

window.addEventListener('accountUpdated', function () {
    alert('تم تحديث بيانات الحساب بنجاح!');
});

window.addEventListener('accountDeleted', function () {
    alert('تم حذف الحساب بنجاح!');
});
        
    </script>